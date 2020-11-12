<?php


class db
{
    // db credentials
    //
    // Host: '127.0.0.1', instead of 'localhost', so I can be connectecd to VPN.
    // Being VPN connected and using 'localhost' won't load the page. It might be a XAMPP thing, not sure about that.
    // Need VPN to work with the free APIs - used so much data off them, that connection from my IP is slowed/blocked.
    private $dbHost = '127.0.0.1';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'huddland-parliament';

    function __construct()
    {
        // establish DB connection
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
    // all requirements are met => add to DB; else false;
    // in the end redirect to manage page
    public function addMP()
    {
        $validate = new validate();
        if ($data = $validate->validatedPostData(0)) {
            $query = "INSERT INTO members (firstname, lastname, date_of_birth, party_id, constituency_id) VALUES (?, ?, ?, ?, ?)";
            $parameters = [$data['firstname'], $data['lastname'], $data['dateOfBirth'], $data['party'], $data['constituency']];
            // insertQuery() returns lastInsertId(), so $mpID === members.id
            $mpID = $this->insertQuery($query, $parameters);

            $query = "INSERT INTO interest_member (member_id, interest_id) VALUES (?, ?)";
            foreach ($data['interests'] as $interest) {
                $parameters = [$mpID, $interest];
                $this->insertQuery($query, $parameters);
            }
            $_SESSION['addMPdetails'] = $data;
            $parliament = new parliament();
            $validate->message(0);
            $parliament->unsetSession();
        }
        header('Location: manage.php?mp');
    }

    // add party function
    //
    // get and validate POST data
    // all requirements are met => add to DB; else false;
    // in the end redirect to manage page
    public function addPARTY()
    {
        $validate = new validate();
        if ($data = $validate->validatedPostData(1)) {
            $query = "INSERT INTO parties (name, date_of_foundation, principal_colour) VALUES (?, ?, ?)";
            $parameters = [$data['partyName'], $data['dateOfFoundation'], $data['principalColour']];
            $this->insertQuery($query, $parameters);
            $_SESSION['addPARTYdetails'] = $data;
            $parliament = new parliament();
            $validate->message(1);
            $parliament->unsetSession();
        }
        header('Location: manage.php?party');
    }

    // add interest function
    //
    // get and validate POST data
    // all requirements are met => add to DB; else false;
    // in the end redirect to manage page
    public function addINTEREST()
    {
        $validate = new validate();
        if ($data = $validate->validatedPostData(2)) {
            $query = "INSERT INTO interests (name) VALUES (?)";
            $this->insertQuery($query, [$data['interestName']]);
            $_SESSION['addINTERESTdetails'] = $data;
            $parliament = new parliament();
            $validate->message(2);
            $parliament->unsetSession();
        }
        header('Location: manage.php?interest');
    }

    // add constituency function
    //
    // get and validate POST data
    // all requirements are met => add to DB; else false;
    // in the end redirect to manage page
    public function addCONSTITUENCY()
    {
        $validate = new validate();
        if ($data = $validate->validatedPostData(3)) {
            $query = "INSERT INTO constituencies (region, electorate) VALUES (?, ?)";
            $this->insertQuery($query, [$data['constituencyRegion'], $data['electorate']]);
            $_SESSION['addCONSTITUENCYdetails'] = $data;
            $parliament = new parliament();
            $validate->message(3);
            $parliament->unsetSession();
        }
        header('Location: manage.php?constituency');
    }

    // functions to get all rows from certain category
    //
    // all MPs and their parties + colour
    public function getAllMp()
    {
        $query = "SELECT members.id, members.firstname, members.lastname, parties.name,parties.principal_colour FROM members
        LEFT JOIN parties ON parties.id = members.party_id";
        return $this->selectQuery($query);
    }

    // all parties
    public function getAllParties()
    {
        $query = "SELECT id, name, date_of_foundation, principal_colour FROM parties";
        return $this->selectQuery($query);
    }

    // all interests
    public function getAllInterests()
    {
        $query = "SELECT id, name FROM interests";
        return $this->selectQuery($query);
    }

    // all constituencies
    public function getAllConstituencies()
    {
        $query = "SELECT id, region, electorate FROM constituencies";
        return $this->selectQuery($query);
    }

    // number of functions used in search feature
    // 
    //
    public function searchMPname($MPname)
    {
        // if more than 1 word
        // match firstname and lastname
        if (count(explode(' ', $MPname)) > 1) {
            $firstname = explode(' ', $MPname)[0];
            $lastname = str_replace($firstname . ' ', '', $MPname);
            $query = "SELECT id FROM members WHERE firstname = ? AND lastname = ?";
            $result = $this->selectQuery($query, [$firstname, $lastname]);

            // if 1 word, match firstname or lastname
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

    // function to get details of all MPs matching the search criteria
    // to be send as response to JS
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
}
