<?php

class validate
{

    // array with required $_POST indexes to perform certain actions
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

    // array with pairs of $_POST index name and it's validation function
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
    private $message = []; // store confirmation messages

    // validate single word
    // 
    // length >= 3
    // only letters and both: ' -
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

    // validate number for electorate
    // 
    // value 30000 - 200000
    // trim whitespace and , . -
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

    public function multipleWords($string, $field)
    {
        $explode = explode(' ', $string);
        $result = true;
        if ($field == 'principalColour') {
            foreach ($explode as $str) {
                if (preg_match("/^[A-Za-z]{3,}$/", $str)) {
                } else {
                    $result = false;
                }
            }
        } else {
            foreach ($explode as $str) {
                if (preg_match("/^[A-Za-z'-]{3,}$/", $str)) {
                } else {
                    $result = false;
                }
            }
        }
        if ($result === true) {
            $_SESSION[$field] = $string;
            return $string;
        } else {
            if ($field == 'partyName') {
                return $this->error(2);
            } else if ($field == 'principalColour') {
                return $this->error(9);
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

    public function foundYear($foundationYear)
    {

        if (preg_match('/^[0-9]{4}$/', $foundationYear)) {
            $foundationYear = intval($foundationYear);
            $currentYear = intval(date('Y'));
            $diff = $currentYear - $foundationYear;
            // not older than 200 years
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
    // before '@':
    // set of letters and _+&*-. 
    // after '@':
    // letters + dot(.) + 2-7 letters
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
    public function id($id, $table)
    {
        $db = new db();
        $fieldName = $table;
        $table == 'party' ? $table = $db->getAllParties() : ($table == 'mp' ? $table = $db->getAllMp() : ($table == 'constituency' ? $table = $db->getAllConstituencies() : false));
        //   echo var_dump($parties);
        $inTable = false;
        if (is_numeric($id)) {
            foreach ($table as $row) {
                if ($row['id'] == $id) {
                    $inTable = $id;
                }
            }
        }
        if ($inTable) {
            $_SESSION[$fieldName] = $inTable;
            return $inTable;
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
    public function interests($ids)
    {
        if ($ids != false) {
            $db = new db();
            $interests = $db->getAllInterests();
            $inTable = true;
            $acceptedIds = [];
            foreach ($ids as $postID) {
                $inTableTemp = false;
                foreach ($interests as $dbID) {
                    // check if POSTED id exists in DB and is not duplicated
                    if ($postID == $dbID['id'] && !in_array($postID, $acceptedIds) && is_numeric($postID)) {
                        $inTableTemp = true;
                        // add valid ID to temp array
                        array_push($acceptedIds, $postID);
                    }
                }
                $inTableTemp == true ? true : $inTable = false;
            }
            $_SESSION['interests'] = $acceptedIds;
            return ($inTable && count($ids) == count($acceptedIds) ? $ids : $this->error(6));
        } else {
            return $this->error(6);
        }
    }

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

    // get post data
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

    // main validation function to be called by 'adding' functions
    // checks if all needed fields for certain actions are filled 
    // also check validation rules
    //
    // action : 0 => addMP, 1 => addPARTY, 2 => addCONST, 3 => addINT
    public function validatedPostData($action)
    {
        $postDATA = $this->rawPostData();
        $result = true;
        $keysArray = $this->postIndexList[$action];
        foreach ($keysArray as $key) {
            if (!array_key_exists($key, $postDATA) || !$this->validateField($key, $postDATA[$key])) {
                $result = false;
            }
        }
        return $result ? $postDATA : false;
    }

    // 
    public function validateField($key, $field)
    {
        $validationMethod = $this->fieldValidationMethod;
        if ($validationMethod[$key] == 'word') {
            return $this->word($field, $key) ? $field : false;
        } else if ($validationMethod[$key] == 'id') {
            return $this->id($field, $key) ? $field : false;
        } else if ($validationMethod[$key] == 'dateOfBirth') {
            return $this->dateOfBirth($field) ? $field : false;
        } else if ($validationMethod[$key] == 'interests') {
            return $this->interests($field) ? $field : false;
        } else if ($validationMethod[$key] == 'email') {
            return $this->email($field) ? $field : false;
        } else if ($validationMethod[$key] == 'multipleWords') {
            return $this->multipleWords($field, $key) ? $field : false;
        } else if ($validationMethod[$key] == 'foundYear') {
            return $this->foundYear($field) ? $field : false;
        } else if ($validationMethod[$key] == 'matchColour') {
            return $this->matchColour($field) ? $field : false;
        } else if ($validationMethod[$key] == 'electorate') {
            return $this->electorate($field) ? $field : false;
        }
    }

    private function error($errorIndex)
    {
        $errorMessages = [
            'Firstname must be longer than 2 letters.', // 0
            'Lastname must be longer than 2 letters.',
            'Party name must be longer than 2 letters.',
            'MP must be between the age of 18 and 95.', // 3
            'Email must be.',
            'Select party.',
            'Select at least 1 interest.', // 6
            'Select constituency.',
            'Year of foundation must be less than 200 years ago.',
            'Principal colour must be longer than 2 and contain only letters.', // 9
            'Year of foundation can not be in future.',
            'Foundation date in wrong format - try "YYYY".',
            'Select principal colour from the dropdown menu.', // 12
            'Interest name must be longer than 2 letters.',
            'Electorate must be a number in rage 30000-200000.',
            'Constituency region must be longer than 2 letters.',
            'Wrong MP ID - try harder <wink>.'
        ];
        array_push($this->error, $errorMessages[$errorIndex]);
        $_SESSION['validError'] = $this->error;
        return false;
    }

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
