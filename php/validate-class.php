<?php

class validate
{

    // array with required $_POST indexes to perform specific actions
    // 0 => add mp
    // 1 => add party  
    // 2 => add interest
    // 3 => add constituency
    private $postIndexList = [
        ['firstname', 'lastname', 'dateOfBirth', 'constituency', 'party', 'interests'],
        ['partyName', 'dateOfFoundation', 'principalColour'],
        ['interestName'],
        ['constituencyRegion', 'electorate'],
    ];

    // array with: $_POST[index] => validation function
    private $fieldValidationMethod =
    [
        'firstname' => 'word',
        'lastname' => 'word',
        'dateOfBirth' => 'dateOfBirth',
        'constituency' => 'id',
        'interests' => 'interests',
        'party' => 'id',
        'email' => 'email',
        'partyName' => 'multipleWords',
        'principalColour' => 'matchColour',
        'dateOfFoundation' => 'foundYear',
        'interestName' => 'multipleWords',
        'constituencyRegion' => 'multipleWords',
        'electorate' => 'electorate'
    ];

    private $error = []; // store errors
    private $message = []; // store messages

    // validate single word: firstname, lastname
    // 
    // length >= 3
    // only letters and '-
    public function word($string, $field)
    {
        if (preg_match("/^[A-Za-z'-]{3,}$/", $string)) {
            $_SESSION[$field] = $string;
            return $string;
        } else {
            if ($field == 'firstname') {
                return $this->error(0);
            } else if ($field == 'lastname') {
                return $this->error(1);
            }
        }
    }

    // validate electorate number
    // 
    // value between 30000 and 200000
    // trim whitespace and ,.
    public function electorate($number)
    {
        $striped = preg_replace('/[.,]/', '', $number);
        if (intval($striped) > 30000 & intval($striped) < 200000) {
            $_SESSION['electorate'] = $striped;
            return $striped;
        } else {
            $this->error(14);
        }
    }

    // validate multiple words: region, interests, party
    //
    // each word length >= 2
    // only letters and '-,
    public function multipleWords($string, $field)
    {
        // split string containing many words
        // into array with separate words
        $explode = explode(' ', $string);
        $result = true;
        // if any of the words doesn't match preg_match
        // set $result = false => function returns false;
        foreach ($explode as $str) {
            if (preg_match("/^[A-Za-z'-,]{2,}$/", $str)) {
            } else {
                $result = false;
            }
        }
        if ($result === true) {
            $_SESSION[$field] = $string;
            return $string;
        } else {
            if ($field == 'partyName') {
                return $this->error(2);
            } else if ($field == 'interestName') {
                return $this->error(13);
            } else if ($field == 'constituencyRegion') {
                return $this->error(15);
            }
        }
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
            $age = intval($age->getAge($dateOfBirth));
        }
        if ($age >= 18 && $age < 95) {
            $_SESSION['dateOfBirth'] = $dateOfBirth;
            return $dateOfBirth;
        } else {
            return $this->error(3);
        }
    }

    // validate party foundation year
    //
    // format YYYY
    // year not in future
    // not older than 200 years
    public function foundYear($foundationYear)
    {

        if (preg_match('/^[0-9]{4}$/', $foundationYear)) {
            $foundationYear = intval($foundationYear);
            $currentYear = intval(date('Y'));
            $diff = $currentYear - $foundationYear;
            if ($diff  < 0) {
                return $this->error(10);
            } else if ($diff  >= 0 && $diff < 200) {
                $_SESSION['dateOfFoundation'] = $foundationYear;
                return $foundationYear;
            }
        } else {
            return $this->error(11);
        }
    }

    // validate email
    //
    // before '@': mix of letters and '_+&*-.'
    // after '@': mix of letter and '-' + dot(.) + 2-7 letters
    public function email($email)
    {
        if (preg_match("/^[a-zA-Z0-9_+&*-]+(?:\.[a-zA-Z0-9_+&*-]+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,7}$/", $email)) {
            $_SESSION['email'] = $email;
            return $email;
        } else {
            return $this->error(4);
        }
    }

    // validate ID: mp, party, constituency
    //
    // is_numeric()
    // exists in given table name 
    // (prevent manipulated data)
    public function id($id, $table)
    {
        $db = new db();
        $fieldName = $table;
        $table == 'party' ? $table = $db->getAllParties() : ($table == 'mp' ? $table = $db->getAllMp() : ($table == 'constituency' ? $table = $db->getAllConstituencies() : false));
        $result = false;
        if (is_numeric($id)) {
            foreach ($table as $row) {
                if ($row['id'] == $id) {
                    $result = $id;
                }
            }
        }
        if ($result) {
            $_SESSION[$fieldName] = $result;
            return $result;
        } else {
            if ($fieldName == 'party') {
                return $this->error(5);
            } else if ($fieldName == 'constituency') {
                return $this->error(7);
            } else if ($fieldName == 'mp') {
                return $this->error(9);
            }
        }
    }

    // validate interest IDs
    // 
    // exists in interests table
    // each posted interest ID is unique
    // (both to prevent manipulated data)
    public function interests($ids)
    {
        if ($ids != false) {
            $db = new db();
            $interests = $db->getAllInterests();
            $result = true;
            $acceptedIds = [];
            foreach ($ids as $postID) {
                $resultTemp = false;
                foreach ($interests as $dbID) {
                    // if POSTED id exists in DB and is not duplicated
                    if ($postID == $dbID['id'] && !in_array($postID, $acceptedIds) && is_numeric($postID)) {
                        $resultTemp = true;
                        // add valid ID to temp array
                        array_push($acceptedIds, $postID);
                    }
                }
                // $resultTemp === false; means, either duplicated or not exist in DB
                // so set $result to false; else do nothing
                $resultTemp == true ? true : $result = false;
            }
            $_SESSION['interests'] = $acceptedIds;
            return ($result && count($ids) == count($acceptedIds) ? $ids : $this->error(6));
        } else {
            return $this->error(6);
        }
    }

    // validate principal colour
    //
    // check if given colour exists in
    // the colours array (148 colours) 
    public function matchColour($givenColour)
    {
        $colours = new parliament();
        if (array_key_exists(preg_replace('/\s+/', '', $givenColour), $colours->principleColoursList)) {
            $_SESSION['principalColour'] = $givenColour;
            return $givenColour;
        } else {
            return $this->error(12);
        }
    }

    // get all post data
    //
    // isset($_POST['index']) ? (add to $post array) : false;
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

    // main validation function to be called by 'add to db' functions
    //
    // checks if all needed fields for specific action are filled 
    // and meet the validation rules
    //
    // action: 0 => addMP, 1 => addPARTY, 2 => addCONST, 3 => addINT
    public function validatedPostData($action)
    {
        $postDATA = $this->rawPostData();
        $result = true;
        // $this->postIndexList - the list with needed $_POST indexes
        $keysArray = $this->postIndexList[$action];

        foreach ($keysArray as $key) {
            if (!array_key_exists($key, $postDATA) || !$this->validateField($key, $postDATA[$key])) {
                $result = false;
            }
        }
        return $result ? $postDATA : false;
    }

    // function to be called by the above "main validation" function
    //
    // it checks the $_POST index of given data (field)
    // and validates it against specific validation function
    // which is in the array $fieldValidationMethod 
    // ($key => $value; key - POST index; $value - validation function)
    public function validateField($key, $field)
    {
        $validationMethod = $this->fieldValidationMethod;
        switch ($validationMethod[$key]) {
            case 'word':
                return $this->word($field, $key);
            case 'id':
                return $this->id($field, $key);
            case 'dateOfBirth':
                return $this->dateOfBirth($field);
            case 'interests':
                return $this->interests($field);
            case 'email':
                return $this->email($field);
            case 'multipleWords':
                return $this->multipleWords($field, $key);
            case 'foundYear':
                return $this->foundYear($field);
            case 'matchColour':
                return $this->matchColour($field);
            case 'electorate':
                return $this->electorate($field);
        }
    }

    // function to set errors
    private function error($errorIndex)
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
            'Try harder <wink>.'
        ];
        array_push($this->error, $errorMessages[$errorIndex]);
        $_SESSION['errorMessage'] = $this->error;
        return false;
    }

    // function to set messages
    public function message($messageIndex)
    {
        $confirmationMessages = [
            [
                'You have succesfully added MP:<br><b>',
                '</b>',
                ($_SESSION['addMPdetails'] ?? false)
            ],
            [
                'You have succesfully added party:<br><b>',
                '</b>',
                ($_SESSION['addPARTYdetails'] ?? false)
            ],
            [
                'You have succesfully added interest:<br><b>',
                '</b>',
                ($_SESSION['addINTERESTdetails'] ?? false)
            ],
            [
                'You have succesfully added constituency:<br><b>',
                '</b>',
                ($_SESSION['addCONSTITUENCYdetails'] ?? false)
            ],
        ];
        array_push($this->message, $confirmationMessages[$messageIndex]);
        $_SESSION['confirmationMessage'] = $this->message;
        return true;
    }
}
