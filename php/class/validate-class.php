<?php

class validate
{

    // array with required $_POST indexes to perform specific actions
    // 0 => add mp
    // 1 => add party  
    // 2 => add interest
    // 3 => add constituency
    private $requiredPOSTindexesForAction = [
        ['firstname', 'lastname', 'dateOfBirth', 'constituency', 'party', 'interests'],
        ['partyName', 'dateOfFoundation', 'principalColour'],
        ['interestName'],
        ['constituencyRegion', 'electorate'],
    ];

    // array with: $_POST[index] => respective validation function
    private $eachPOSTindexValidationMethod =
    [
        'firstname' => 'singleWord',
        'lastname' => 'singleWord',
        'dateOfBirth' => 'dateOfBirth',
        'constituency' => 'id',
        'interests' => 'interests',
        'party' => 'id',
        'email' => 'email',
        'partyName' => 'multipleWords',
        'principalColour' => 'matchColour',
        'dateOfFoundation' => 'foundationYear',
        'interestName' => 'multipleWords',
        'constituencyRegion' => 'multipleWords',
        'electorate' => 'electorate'
    ];

    private $error = [];
    private $message = [];

    // validate single word: firstname, lastname
    // 
    // length >= 3
    // only letters and '-
    public function singleWord($value, $index)
    {
        if (preg_match("/^[A-Za-z'-]{3,}$/", $value)) {
            return $_SESSION[$index] = $value;
        }
        return ($index === 'firstname' ? $this->error(0) : ($index === 'lastname' ? $this->error(1) : false));
    }

    // validate electorate number
    // 
    // number between 30000 and 200000
    // trim whitespace and ,.
    public function electorate($number)
    {
        $striped = intval(preg_replace('/[.,]/', '', $number));
        return ($striped > 30000 && $striped < 200000) ? $_SESSION['electorate'] = $striped : $this->error(14);
    }

    // validate multiple words: region, interest, party
    //
    // each word length >= 2
    // whole string > 2 
    // (accept words like "of" or "as" but not on its own)
    // only letters and '-,
    public function multipleWords($value, $index = false)
    {
        $words = explode(' ', $value);
        $result = true;

        // if any of the words doesn't match -> function returns false;
        foreach ($words as $wrd) {
            if (!preg_match("/^[A-Za-z'-,]{2,}$/", $wrd)) $result = false;
        }

        if ($result === true && strlen($value) > 2) {
            if ($index) $_SESSION[$index] = $value;
            return $value;
        }
        return ($index === 'partyName' ? $this->error(2) : ($index === 'interestName' ? $this->error(13) : ($index === 'constituencyRegion' ? $this->error(15) : false)));
    }

    // validate date of birth
    //
    // format YYYY-MM-DD
    // age >= 18 && age < 95
    public function dateOfBirth($dateOfBirth)
    {
        $age = '';
        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dateOfBirth)) {
            $age = new parliament();
            $age = intval($age->calculateAge($dateOfBirth));
        }
        return ($age >= 18 && $age < 95) ? $_SESSION['dateOfBirth'] = $dateOfBirth : $this->error(3);
    }

    // validate party foundation year
    //
    // format YYYY
    // year not in future
    // not older than 200 years
    public function foundationYear($foundationYear)
    {

        if (preg_match('/^[0-9]{4}$/', $foundationYear)) {
            $foundationYear = intval($foundationYear);
            $currentYear = intval(date('Y'));
            $diff = $currentYear - $foundationYear;

            if ($diff  < 0) {
                return $this->error(10);
            } else if ($diff  >= 0 && $diff < 200) {
                return $_SESSION['dateOfFoundation'] = $foundationYear;
            } else {
                return $this->error(16);
            }
        }
        return $this->error(11);
    }

    // validate email
    //
    // before '@': mix of letters and '_+&*-.'
    // after '@': mix of letter and '-' + dot(.) + 2-7 letters
    public function email($email)
    {
        return (preg_match("/^[a-zA-Z0-9_+&*-]+(?:\.[a-zA-Z0-9_+&*-]+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,7}$/", $email)) ? $_SESSION['email'] = $email : $this->error(4);
    }

    // validate ID: mp, party, constituency
    //
    // is_numeric()
    // exists in given table name 
    // (prevent manipulated data)
    public function id($value, $index, $noSession = false)
    {
        $db = new db();
        $inputName = $index;

        switch ($index) {
            case 'party':
                $IDs = $db->getAllParties();
                break;
            case 'mp':
                $IDs = $db->getAllMp();
                break;
            case 'constituency':
                $IDs = $db->getAllConstituencies();
                break;
            case 'interestSearch':
                $IDs = $db->getAllInterests();
                break;
        }

        $result = false;
        if (is_numeric($value)) {
            foreach ($IDs as $id) {
                if ($id['id'] == $value) $result = $value;
            }
        }

        if ($result) {
            if ($noSession !== 'noSession') $_SESSION[$inputName] = $result;
            return $result;
        } else {
            if ($inputName === 'party') {
                return $this->error(5);
            } else if ($inputName === 'constituency') {
                return $this->error(7);
            } else if ($inputName === 'mp') {
                return $this->error(9);
            }
        }
    }

    // validate interest IDs
    // 
    // each POSTed interest ID is unique
    // and exists in interests table
    // (both to prevent manipulated data)
    public function interests($ids)
    {
        if ($ids != false) {
            $db = new db();
            $interests = $db->getAllInterests();
            $result = true;
            $validIDs = [];

            foreach ($ids as $postID) {
                $resultTemp = false;
                foreach ($interests as $dbID) {
                    // if POSTed ID exists in DB and is not duplicated
                    if ($postID == $dbID['id'] && !in_array($postID, $validIDs) && is_numeric($postID)) {
                        array_push($validIDs, $postID);
                        $resultTemp = true;
                    }
                }
                // $resultTemp === false; means, either the ID is duplicated or not exist in DB -> set $result to false
                $resultTemp === true ? true : $result = false;
            }

            $_SESSION['interests'] = $validIDs;
            return ($result && count($ids) === count($validIDs) ? $ids : $this->error(6));
        }
        return $this->error(6);
    }

    // validate principal colour
    //
    // check if given colour exists in the principal colours array (148 CSS colour names) 
    public function matchColour($principalColour)
    {
        $colours = new parliament();
        return (array_key_exists(preg_replace('/\s+/', '', $principalColour), $colours->principalColoursList)) ?
            $_SESSION['principalColour'] = $principalColour :
            $this->error(12);
    }

    // get POST data
    private function rawPostData()
    {
        $post = [];
        isset($_POST['firstname']) ? $post['firstname'] = $_POST['firstname'] : false;
        isset($_POST['lastname']) ? $post['lastname'] = $_POST['lastname'] : false;
        isset($_POST['dateOfBirth']) ? $post['dateOfBirth'] = $_POST['dateOfBirth'] : false;
        isset($_POST['constituency']) ? $post['constituency'] = $_POST['constituency'] : false;
        isset($_POST['interests']) ? $post['interests'] = $_POST['interests'] : $post['interests'] = false;
        isset($_POST['party']) ? $post['party'] = $_POST['party'] : false;
        isset($_POST['partyName']) ? $post['partyName'] = $_POST['partyName'] : false;
        isset($_POST['dateOfFoundation']) ? $post['dateOfFoundation'] = $_POST['dateOfFoundation'] : false;
        isset($_POST['principalColour']) ? $post['principalColour'] = $_POST['principalColour'] : false;
        isset($_POST['interestName']) ? $post['interestName'] = $_POST['interestName'] : false;
        isset($_POST['constituencyRegion']) ? $post['constituencyRegion'] = $_POST['constituencyRegion'] : false;
        isset($_POST['electorate']) ? $post['electorate'] = $_POST['electorate'] : false;
        return $post;
    }

    // main validation function called by 'ADD to DB' functions
    //
    // validate all required input fields for specific action
    public function validatePostData($action)
    {
        $rawPostData = $this->rawPostData();
        $keysArray = $this->requiredPOSTindexesForAction[$action];
        $result = true;

        foreach ($keysArray as $index) {
            if (!array_key_exists($index, $rawPostData) || !$this->validateField($index, $rawPostData[$index])) $result = false;
        }
        return $result ? $rawPostData : false;
    }

    // function to be called by the above "main validation" function
    //
    // validate $value against a specific validation function
    public function validateField($index, $value)
    {
        $validationMethod = $this->eachPOSTindexValidationMethod;
        switch ($validationMethod[$index]) {
            case 'singleWord':
                return $this->singleWord($value, $index);
            case 'id':
                return $this->id($value, $index);
            case 'dateOfBirth':
                return $this->dateOfBirth($value);
            case 'interests':
                return $this->interests($value);
            case 'email':
                return $this->email($value);
            case 'multipleWords':
                return $this->multipleWords($value, $index);
            case 'foundationYear':
                return $this->foundationYear($value);
            case 'matchColour':
                return $this->matchColour($value);
            case 'electorate':
                return $this->electorate($value);
        }
    }

    public function error($errorIndex)
    {
        $errorMessages = [
            'Firstname must be longer than 2 characters.', // 0
            'Lastname must be longer than 2 characters.',
            'Party name must be longer than 2 characters.',
            'MP must be between the age of 18 and 95.', // 3
            'Email must be.',
            'Select party.',
            'Select at least 1 interest.', // 6
            'Select constituency.',
            'Year of foundation must be less than 200 years ago.',
            'Principal colour must be longer than 2 letters.', // 9
            'Year of foundation can not be in future.',
            'Wrong format of foundation year (try YYYY).',
            'Select principal colour from the dropdown menu.', // 12
            'Interest name must be longer than 2 characters.',
            'Electorate must be a number in range 30000-200000.',
            'Constituency region name must be longer than 2 characters.', // 15
            'Party can not be older than 200 years.',
            'Select NOT represented constituency.'
        ];
        array_push($this->error, $errorMessages[$errorIndex]);
        $_SESSION['errorMessage'] = $this->error;
        return false;
    }

    public function message($messageIndex)
    {
        $confirmationMessages = [
            [
                'You have succesfully added MP: <b>',
                '</b>',
                ($_SESSION['addMPdetails'] ?? false)
            ],
            [
                'You have succesfully added party: <b>',
                '</b>',
                ($_SESSION['addPARTYdetails'] ?? false)
            ],
            [
                'You have succesfully added interest: <b>',
                '</b>',
                ($_SESSION['addINTERESTdetails'] ?? false)
            ],
            [
                'You have succesfully added constituency: <b>',
                '</b>',
                ($_SESSION['addCONSTITUENCYdetails'] ?? false)
            ],
        ];

        array_push($this->message, $confirmationMessages[$messageIndex]);
        return $_SESSION['confirmationMessage'] = $this->message;
    }

    // encode/escape untrusted data - XSS protection.
    function entitiesHTML($untrustedData)
    {
        return htmlentities($untrustedData, ENT_QUOTES, 'UTF-8');
    }
}
