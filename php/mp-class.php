<?php


class mp
{
    // private $db;
    // private $mpID;
    // private $firstName;
    // private $lastName;
    // private $dateOfBirth;
    public $error = array();
    // array with all needed POST data keys to AMEND MP DATA
    private $amendMPkeys = ['mpFirstname', 'mpLastname', 'mpDoB', 'mpConstituencyID', 'mpInterests', 'mpPartyID'];
    // array with all needed POST data keys to AMEND PARTY DATA
    private $amendPARTYkeys = ['mpFirstname', 'mpLastname', 'mpDoB', 'mpConstituencyID', 'mpInterests', 'mpPartyID'];

    function __construct()
    {
        $this->mpID = isset($_GET['mpID']) && is_numeric($_GET['mpID']) ? $_GET['mpID'] : ($_SESSION['mpID'] ?? false);
        $this->partyID = isset($_GET['partyID']) && is_numeric($_GET['partyID']) ? $_GET['partyID'] : ($_SESSION['partyID'] ?? false);
        $this->db = new db();
    }

    private function getAllMp()
    {
        $query = "SELECT id, firstname, lastname FROM members";
        return $this->db->selectQuery($query);
    }

    private function getAllParties()
    {
        $query = "SELECT id, name, date_of_foundation, principal_colour FROM parties";
        return $this->db->selectQuery($query);
    }

    private function getAllInterests()
    {
        $query = "SELECT id, name FROM interests";
        return $this->db->selectQuery($query);
    }

    private function getAllConstituencies()
    {
        $query = "SELECT id, region, electorate FROM constituencies";
        return $this->db->selectQuery($query);
    }

    public function getMpDetails()
    {
        // get all possible information about single MP; used GROUP_CONCAT to not get 
        // duplicated rows with only one different value, it puts all interests
        // into one string separated with commas
        $query =
            "SELECT members.id, members.firstname, members.party_id, members.lastname, members.date_of_birth, parties.name, parties.date_of_foundation, parties.principal_colour, constituencies.region, constituencies.electorate, constituencies.id AS constiID,
            GROUP_CONCAT(interests.name SEPARATOR ', ') AS interests,
            GROUP_CONCAT(interests.id SEPARATOR ',') AS interestsID
            FROM members
            LEFT JOIN parties ON parties.id = members.party_id
            LEFT JOIN constituencies ON constituencies.id = members.constituency_id 
            LEFT JOIN interest_member ON interest_member.member_id = members.id
            LEFT JOIN interests ON interests.id = interest_member.interest_id
            WHERE members.id = ?";
        $param = array($this->mpID);
        $mp = $this->db->selectQuery($query, $param);
        if ($mp[0]['firstname'] != NULL) {
            $_SESSION['mpID'] = $mp[0]['id'];
            $_SESSION['mpFirstName'] = $mp[0]['firstname'];
            $_SESSION['mpLastName'] = $mp[0]['lastname'];
            $_SESSION['mpDoB'] = $mp[0]['date_of_birth'];
            $_SESSION['mpConstituencyID'] = $mp[0]['constiID'];
            $_SESSION['mpInterestIDs'] = explode(',', $mp[0]['interestsID']);
            $_SESSION['mpPartyID'] = $mp[0]['party_id'];
            $this->mpPartyID = $mp[0]['party_id'];
            $this->constituencyID = $mp[0]['constiID'];
            $this->interestsID = explode(',', $mp[0]['interestsID']);
            return $mp;
        } else {
            unset($_SESSION['mpID']);
            return false;
        }
    }

    private function setSessionMpDetails()
    {
    }

    // calculate Age, from format YYYY-MM-DD
    public function getAge($dateOfBirth)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');
        $mpYear = substr($dateOfBirth, 0, 4);
        $mpMonth = substr($dateOfBirth, 5, 2);
        $mpDay = substr($dateOfBirth, 8, 2);

        // had birthday this year already? has birthday today? 
        $age = $currentYear - $mpYear;
        if ($currentMonth < $mpMonth) {
            $age++;
        } else if ($currentMonth == $mpMonth && $currentDay <= $mpDay) {
            $age++;
        }

        return $age;
    }

    public function displayMpList()
    {
        if ($mps = $this->getAllMp()) {
            foreach ($mps as $mp) {
                echo '<a href="mp-details.php?mpID=' . $mp['id'] . '">' . $mp['firstname'] . ' ' . $mp['lastname'] . '</a><br>';
            }
        } else {
            return false;
        }
    }

    public function displayMpSelection($amendOrRemove)
    {
        $amendOrRemove = $amendOrRemove == 'amend' ? 'mpList' : 'mpRemoveList';
        if ($mps = $this->getAllMp()) {
            $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="' . $amendOrRemove . '" name="' . $amendOrRemove . '"><option value="0"></option>';
            $selectEnd = '</select>';
            $options = '';
            foreach ($mps as $mp) {
                if ($mp['id'] == $this->mpID) {
                    $options .= '<option selected value="' . $mp['id'] . '">' . $mp['firstname'] . ' ' . $mp['lastname'] . '</option>';
                } else {
                    $options .= '<option value="' . $mp['id'] . '">' . $mp['firstname'] . ' ' . $mp['lastname'] . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        } else {
            return false;
        }
    }

    public function displayPartiesList($inMpOrPartySection)
    {
        if ($parties = $this->getAllParties()) {
            if ($inMpOrPartySection == 'amendParty') {
                $partyId = $this->partyID;
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="partyAmendList" name="partyAmendList"><option value="0"></option>';
            } else if ($inMpOrPartySection == 'removeParty') {
                $partyId = $this->partyID;
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="partyRemoveList" name="partyRemoveList"><option value="0"></option>';
            } else if ($inMpOrPartySection == 'amendMp') {
                $partyId = $this->mpPartyID;
                $selectStart = '<select id="party" name="party"><option value="0"></option>';
            } else if ($inMpOrPartySection == 'addMp') {
                $partyId = 0;
                $selectStart = '<select id="party" name="party"><option value="0"></option>';
            }
            $selectEnd = '</select>';
            $options = '';
            foreach ($parties as $party) {
                if ($party['id'] == $partyId) {
                    $options .= '<option selected value="' . $party['id'] . '">' . $party['name'] . '</option>';
                } else {
                    $options .= '<option value="' . $party['id'] . '">' . $party['name'] . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        } else {
            return false;
        }
    }

    public function getPartyDetails()
    {
        // get all PARTY details
        $query =
            "SELECT id, name, date_of_foundation, principal_colour FROM parties
            WHERE id = ?";
        $param = array($this->partyID);
        $party = $this->db->selectQuery($query, $param);
        if (count($party) > 0) {
            $_SESSION['partyID'] = $party[0]['id'];
            $_SESSION['partyName'] = $party[0]['name'];
            $_SESSION['partyDoF'] = $party[0]['date_of_foundation'];
            $_SESSION['partyPrincipalColour'] = $party[0]['principal_colour'];

            $this->partyID = $party[0]['id'];
            $this->dateOfFoundation = $party[0]['date_of_foundation'];
            $this->principalColour = $party[0]['principal_colour'];
            return $party;
        } else {
            unset($_SESSION['partyID']);
            return false;
        }
    }

    public function displayConstituenciesList($inMpOrConstituencySection)
    {
        if ($constituencies = $this->getAllConstituencies()) {
            if ($inMpOrConstituencySection == 'constituency') {
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="constituency" name="constituency"><option value="0"></option>';
            } else if ($inMpOrConstituencySection == 'amendMp') {
                $selectStart = '<select id="constituency" name="constituency">';
            } else if ($inMpOrConstituencySection == 'addMp') {
                $this->constituencyID = 0;
                $selectStart = '<select id="constituency" name="constituency"><option value="0"></option>';
            }
            $selectEnd = '</select>';
            $options = '';
            foreach ($constituencies as $constituency) {
                if ($constituency['id'] == $this->constituencyID) {
                    $options .= '<option selected value="' . $constituency['id'] . '">' . $constituency['region'] . '</option>';
                } else {
                    $options .= '<option value="' . $constituency['id'] . '">' . $constituency['region'] . '</option>';
                }
            }
            return $selectStart . $options . $selectEnd;
        } else {
            return false;
        }
    }
    // if amend leave parameter blank, if adding, set to false
    public function displayInterestsList($amendAdd = true)
    {
        $interests = $this->getAllInterests();
        $input = '';
        foreach ($interests as $interest) {
            if (in_array($interest['id'], ($amendAdd ? $this->interestsID : array()))) {
                $input .= '<div class="interests"><input type="checkbox" checked name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
            } else {
                $input .= '<div class="interests"><input type="checkbox" name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
            }
        }
        return $input;
    }

    // private function validateEmail($postEmail)
    // {
    //     return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $postEmail)) ? false : true;
    // }

    private function getPostData()
    {
        $_POST['firstname'] ? $postDATA['mpFirstname'] = $_POST['firstname'] : false;
        $_POST['lastname'] ? $postDATA['mpLastname'] = $_POST['lastname'] : false;
        $_POST['dateOfBirth'] ? $postDATA['mpDoB'] = $_POST['dateOfBirth'] : false;
        $_POST['constituency'] ? $postDATA['mpConstituencyID'] = $_POST['constituency'] : false;
        isset($_POST['interests']) ? $postDATA['mpInterests'] = $_POST['interests'] : false;
        $_POST['party'] ? $postDATA['mpPartyID'] = $_POST['party'] : false;
        return $postDATA;
    }

    private function getSessionData()
    {
        $_SESSION['mpFirstName'] ? $sessionDATA['mpFirstname'] = $_SESSION['mpFirstName'] : false;
        $_SESSION['mpLastName'] ? $sessionDATA['mpLastname'] = $_SESSION['mpLastName'] : false;
        $_SESSION['mpDoB'] ? $sessionDATA['mpDoB'] = $_SESSION['mpDoB'] : false;
        $_SESSION['mpConstituencyID'] ? $sessionDATA['mpConstituencyID'] = $_SESSION['mpConstituencyID'] : false;
        $_SESSION['mpInterestIDs'] ? $sessionDATA['mpInterests'] = $_SESSION['mpInterestIDs'] : false;
        $_SESSION['mpPartyID'] ? $sessionDATA['mpPartyID'] = $_SESSION['mpPartyID'] : false;
        return $sessionDATA;
    }

    // build query based on action type: 0 => amendMP, 1 => amendPARTY, 2 => amendCONST, 3 => amendINT
    private function hasEnoughPostData($postDATA, $action)
    {
        $result = true;
        $keysArray = ($action == 0 ? $this->amendMPkeys : ($action == 1 ? $this->amendPARTYkeys : array('asdasd')));
        foreach ($keysArray as $key) {
            if (!array_key_exists($key, $postDATA)) {
                $result = false;
            }
        }
        return $result;
    }


    public function amendMP()
    {
        $postDATA = $this->getPostData();
        $checkPost = $this->hasEnoughPostData($postDATA, 0);
        if ($checkPost) {
            echo "post:<br><br><pre>";
            echo var_dump($this->getPostData());
            echo "</pre>";
            echo "session:<br><br><pre>";
            echo var_dump($this->getSessionData());
            echo "</pre>";

            $query = "DELETE interest_member FROM interest_member WHERE interest_member.member_id = ?";
            $removeMPinterest = $this->db->updateDeleteInsertQuery($query, array($this->mpID));
            if ($removeMPinterest) {
                $query = "UPDATE members SET firstname = ?, lastname = ?, date_of_birth = ?, party_id = ?, constituency_id = ? WHERE id = ?";
                $params = array($postDATA['mpFirstname'], $postDATA['mpLastname'], $postDATA['mpDoB'], $postDATA['mpPartyID'], $postDATA['mpConstituencyID'], $this->mpID);
                $amendMP = $this->db->updateDeleteInsertQuery($query, $params);
                if ($amendMP) {
                    $result = true;
                    foreach ($postDATA['mpInterests'] as $interest) {
                        $query = "INSERT INTO interest_member (member_id, interest_id) VALUES (?, ?)";
                        $insertMPinterests = $this->db->updateDeleteInsertQuery($query, array($this->mpID, $interest));
                        if (!$insertMPinterests) {
                            $result = false;
                        }
                    }
                } else {
                    return false;
                }
                return $result;
            } else {
                return false;
            }
        }
    }

    public function removeMP()
    {
        // DELETE MP (members table) alongside with interests (interest_member table)
        $query = "DELETE interest_member, members FROM interest_member INNER JOIN members ON members.id = interest_member.member_id WHERE interest_member.member_id = ?";
        $removeMP = $this->db->updateDeleteInsertQuery($query, array($this->mpID));
        if ($removeMP) {
            unset($_SESSION['mpID']);
            header('Location: ../manage.php?removedMP');
            return true;
        } else {
            unset($_SESSION['mpID']);
            header('Location: ../manage.php?error');
            return false;
        }
    }
}
