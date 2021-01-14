<?php


class db
{
    // Host: '127.0.0.1', instead of 'localhost', so I can be connectecd to VPN.
    // Being VPN connected and using 'localhost' won't load the page. It might be a XAMPP thing, not sure about that.
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'huddland-parliament';

    function __construct()
    {
        $this->mpID = isset($_GET['mpID']) && is_numeric($_GET['mpID']) ? $_GET['mpID'] : false;
        try {
            $this->conn = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUser, $this->dbPass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // simplifie select queries
    public function selectQuery($query, $parameters = false)
    {
        try {
            $query = $this->conn->prepare($query);
            ($parameters) ?
                $query->execute($parameters) :
                $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // simplifie insert queries
    public function insertQuery($query, $parameters)
    {
        try {
            $query = $this->conn->prepare($query);
            $query->execute($parameters);
            return $query ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // add MP function
    //
    // get and validate POST data
    // all requirements are met => add to DB
    // in the end redirect to manage page
    public function addMP()
    {
        $validate = new validate();
        if ($data = $validate->validatePostData(0)) {
            // check if constituency ID isn't manually changed to represented one
            if (!$this->isConstRepresented($data['constituency'])) {
                $query = "INSERT INTO members (firstname, lastname, date_of_birth, party_id, constituency_id) VALUES (?, ?, ?, ?, ?)";
                $parameters = [$data['firstname'], $data['lastname'], $data['dateOfBirth'], $data['party'], $data['constituency']];
                $mpID = $this->insertQuery($query, $parameters);

                $query = "INSERT INTO interest_member (member_id, interest_id) VALUES (?, ?)";
                foreach ($data['interests'] as $interest) {
                    // insertQuery() returns lastInsertId() => $mpID === members.id
                    $parameters = [$mpID, $interest];
                    $this->insertQuery($query, $parameters);
                }

                $_SESSION['addMPdetails'] = $data;
                $parliament = new parliament();
                $validate->message(0);
                $parliament->unsetInputFieldSessions();
            } else {
                $validate->error(17);
            }
        }
        header('Location: manage.php?mp');
    }

    // add party function
    //
    // get and validate POST data
    // all requirements are met => add to DB
    // in the end redirect to manage page
    public function addPARTY()
    {
        $validate = new validate();
        if ($data = $validate->validatePostData(1)) {
            $query = "INSERT INTO parties (name, date_of_foundation, principal_colour) VALUES (?, ?, ?)";
            $parameters = [$data['partyName'], $data['dateOfFoundation'], $data['principalColour']];
            $this->insertQuery($query, $parameters);
            $_SESSION['addPARTYdetails'] = $data;
            $parliament = new parliament();
            $validate->message(1);
            $parliament->unsetInputFieldSessions();
        }
        header('Location: manage.php?party');
    }

    // add interest function
    //
    // get and validate POST data
    // all requirements are met => add to DB
    // in the end redirect to manage page
    public function addINTEREST()
    {
        $validate = new validate();
        if ($data = $validate->validatePostData(2)) {
            $query = "INSERT INTO interests (name) VALUES (?)";
            $this->insertQuery($query, [$data['interestName']]);
            $_SESSION['addINTERESTdetails'] = $data;
            $parliament = new parliament();
            $validate->message(2);
            $parliament->unsetInputFieldSessions();
        }
        header('Location: manage.php?interest');
    }

    // add constituency function
    //
    // get and validate POST data
    // all requirements are met => add to DB
    // in the end redirect to manage page
    public function addCONSTITUENCY()
    {
        $validate = new validate();
        if ($data = $validate->validatePostData(3)) {
            $query = "INSERT INTO constituencies (region, electorate) VALUES (?, ?)";
            $this->insertQuery($query, [$data['constituencyRegion'], $data['electorate']]);
            $_SESSION['addCONSTITUENCYdetails'] = $data;
            $parliament = new parliament();
            $validate->message(3);
            $parliament->unsetInputFieldSessions();
        }
        header('Location: manage.php?constituency');
    }

    // functions to get all rows from specific table
    //
    // 
    public function getAllMp()
    {
        $query = "SELECT members.id, members.firstname, members.lastname, parties.name,parties.principal_colour FROM members
        LEFT JOIN parties ON parties.id = members.party_id";
        return $this->selectQuery($query);
    }

    public function getAllParties()
    {
        $query = "SELECT id, name, date_of_foundation, principal_colour FROM parties";
        return $this->selectQuery($query);
    }

    public function getAllInterests()
    {
        $query = "SELECT id, name FROM interests";
        return $this->selectQuery($query);
    }

    public function getAllConstituencies()
    {
        $query = "SELECT id, region, electorate FROM constituencies";
        return $this->selectQuery($query);
    }

    public function isConstRepresented($constituencyIDtoCheck)
    {
        $query = "SELECT GROUP_CONCAT(constituency_id) as constID FROM members";
        $representedConstituencies = explode(',', $this->selectQuery($query)[0]['constID']);
        return (in_array($constituencyIDtoCheck, $representedConstituencies));
    }

    // functions used in search feature
    // 
    //
    public function searchMPname($MPname)
    {
        // (more than 1 word) ? match firstname AND lastname;
        if (count(explode(' ', $MPname)) > 1) {
            $firstname = explode(' ', $MPname)[0];
            $lastname = str_replace($firstname . ' ', '', $MPname);
            $query = "SELECT id FROM members WHERE firstname = ? AND lastname = ?";
            $result = $this->selectQuery($query, [$firstname, $lastname]);

            // (1 word) ? match firstname OR lastname;
        } else if (count(explode(' ', $MPname)) === 1) {
            $query = "SELECT id FROM members WHERE firstname = ? OR lastname = ?";
            $result = $this->selectQuery($query, [$MPname, $MPname]);
        }
        return (count($result) > 0 ? $result : false);
    }

    public function searchMpPartyID($partyID)
    {
        $query = "SELECT id FROM members WHERE party_id = ?";
        return $this->selectQuery($query, [$partyID]);
    }

    public function searchMpInterestID($interestID)
    {
        $query = "SELECT member_id AS id FROM interest_member WHERE interest_id = ?";
        return $this->selectQuery($query, [$interestID]);
    }

    public function searchMpConstituencyID($constituencyID)
    {
        $query = "SELECT id FROM members WHERE constituency_id = ?";
        return $this->selectQuery($query, [$constituencyID]);
    }

    // function to get details of all MPs matching users's search criteria; to be send as response to JS
    public function getMatchingSearchMP($arrayWithIDs)
    {
        $results = [];
        foreach ($arrayWithIDs as $MPid) {
            $query = "SELECT members.id, members.firstname, members.lastname, parties.name,parties.principal_colour FROM members
            LEFT JOIN parties ON parties.id = members.party_id
            WHERE members.id = ?";
            $singleResult = $this->selectQuery($query, [$MPid])[0];
            array_push($results, $singleResult);
        }
        return $results;
    }

    public function getSingleMpDetails()
    {
        // GROUP_CONCAT to gather all interests as a single string
        $query =
            "SELECT members.id, members.firstname, members.party_id, members.lastname, members.date_of_birth, 
            parties.name, parties.date_of_foundation, parties.principal_colour, constituencies.region, 
            constituencies.electorate, constituencies.id AS constiID,
            GROUP_CONCAT(interests.name SEPARATOR ', ') AS interests,
            GROUP_CONCAT(interests.id SEPARATOR ',') AS interestsID
            FROM members
            LEFT JOIN parties ON parties.id = members.party_id
            LEFT JOIN constituencies ON constituencies.id = members.constituency_id 
            LEFT JOIN interest_member ON interest_member.member_id = members.id
            LEFT JOIN interests ON interests.id = interest_member.interest_id
            WHERE members.id = ?";
        $param = [$this->mpID];
        $mp = $this->selectQuery($query, $param);
        return ($mp[0]['firstname'] !== null) ? $mp : false;
    }
}
