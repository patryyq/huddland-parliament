<?php

class mp
{
    private $userId;
    private $db;
    private $mpId;

    function __construct()
    {
        $this->db = new db();
        $this->mpId = isset($_GET['id']) && ctype_digit($_GET['id']) ? $_GET['id'] : FALSE;
    }

    public function displayMpList()
    {
        if ($mps = $this->getAllMp()) {
            foreach ($mps as $mp) {
                echo '<a href="mp-details.php?id=' . $mp['id'] . '">' . $mp['firstname'] . ' ' . $mp['lastname'] . '</a><br>';
            }
        } else {
            return false;
        }
    }

    private function getAllMp()
    {
        $query = "SELECT id, firstname, lastname FROM members";
        $mp = $this->db->dbQuery($query);
        return $mp;
    }

    public function getMpDetails()
    {
        // get all possible information about single MP; used GROUP_CONCAT to not get 
        // duplicated rows with only one different value, it puts all interests
        // into one string separated with commas
        $query =
            "SELECT members.firstname, members.lastname, members.date_of_birth, parties.name, parties.date_of_foundation, parties.principal_colour, constituencies.region, constituencies.electorate, 
            GROUP_CONCAT(interests.name SEPARATOR ', ') AS interests
            FROM members 
            INNER JOIN parties ON parties.id = members.party_id 
            INNER JOIN constituencies ON constituencies.id = members.constituency_id 
            INNER JOIN interest_member ON interest_member.member_id = members.id
            INNER JOIN interests ON interests.id = interest_member.interest_id
            WHERE members.id = ?";
        $param = array($this->mpId);
        $mp = $this->db->dbQuery($query, $param);
        return $mp;
    }

    // calculate MP's age, based on DoB from DB
    public function getMpAge($dateOfBirth)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');
        $mpYear = substr($dateOfBirth, 0, 4);
        $mpMonth = substr($dateOfBirth, 5, 2);
        $mpDay = substr($dateOfBirth, 8, 2);

        // had birthday this year already? has birthday today? 
        $age = $currentYear - $mpYear - 1;
        if ($currentMonth < $mpMonth) {
            $age++;
        } else if ($currentMonth == $mpMonth && $currentDay <= $mpDay) {
            $age++;
        }

        return $age;
    }
}
