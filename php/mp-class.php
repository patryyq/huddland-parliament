<?php

class mp
{
    // private $db;
    // private $mpID;
    // private $firstName;
    // private $lastName;
    // private $dateOfBirth;
    // private $partyID;

    function __construct()
    {
        $this->db = new db();
        $this->mpID = isset($_GET['mpID']) && ctype_digit($_GET['mpID']) ? $_GET['mpID'] : ($_SESSION['mpID'] ?? false);
        $this->partyID = isset($_GET['partyID']) && ctype_digit($_GET['partyID']) ? $_GET['partyID'] : ($_SESSION['partyID'] ?? false);
        $this->error = array();
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
            INNER JOIN parties ON parties.id = members.party_id 
            INNER JOIN constituencies ON constituencies.id = members.constituency_id 
            INNER JOIN interest_member ON interest_member.member_id = members.id
            INNER JOIN interests ON interests.id = interest_member.interest_id
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

            // if (!isset($_GET['amendPARTY']) && !isset($_GET['removePARTY'])) {
            //     $_SESSION['partyID'] = $mp[0]['party_id'];
            //     $this->partyID = $mp[0]['party_id'];
            // }
            $this->mpPartyID = $mp[0]['party_id'];
            $this->constituencyID = $mp[0]['constiID'];
            $this->interestsID = explode(',', $mp[0]['interestsID']);
            return $mp;
        } else {
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
            $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="' . $amendOrRemove . '" name="' . $amendOrRemove . '"><option value=""></option>';
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
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="partyAmendList" name="partyAmendList"><option value=""></option>';
            } else if ($inMpOrPartySection == 'removeParty') {
                $partyId = $this->partyID;
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="partyRemoveList" name="partyRemoveList"><option value=""></option>';
            } else if ($inMpOrPartySection == 'mp') {
                $partyId = $this->mpPartyID;
                $selectStart = '<select id="party" name="party">';
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
            $this->partyID = $party[0]['id'];
            $this->dateOfFoundation = $party[0]['date_of_foundation'];
            $this->principalColour = $party[0]['principal_colour'];
            return $party;
        } else {
            return false;
        }
    }

    public function displayConstituenciesList($inMpOrConstituencySection)
    {
        if ($constituencies = $this->getAllConstituencies()) {
            if ($inMpOrConstituencySection == 'constituency') {
                $selectStart = '<select style="font-weight:bold;border:4px solid #03339d" id="constituency" name="constituency"><option value=""></option>';
            } else if ($inMpOrConstituencySection == 'mp') {
                $selectStart = '<select id="constituency" name="constituency">';
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

    public function displayInterestsList()
    {
        $interests = $this->getAllInterests();
        $input = '';
        foreach ($interests as $interest) {
            if (in_array($interest['id'], $this->interestsID)) {
                $input .= '<div class="interests"><input type="checkbox" checked name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
            } else {
                $input .= '<div class="interests"><input type="checkbox" name="interests[]" value="' . $interest['id'] . '">' . $interest['name'] . '</div>';
            }
        }
        return $input;
    }

    private function validateEmail($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    private function validatePOST($actionType)
    {
        $firstnamePOST = $_POST['firstname'];
        $_POST['lastname'];
        $_POST['dateOfBirth'];
        $_POST['party'];
        $_POST['constituency'];
        echo '<pre>';
        echo var_dump($_POST['interests']);
        echo '</pre>';
    }
    public function amendMP()
    {
    }

    public function removeMP()
    {
        // DELETE MP alongside with interests from interest_member
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
