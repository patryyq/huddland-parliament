<?php


class db
{
    // db credentials
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'huddland-parliament';

    function __construct()
    {
        // on object initialisation, connect to db
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

    // below number of functions to get all rows from certain category
    public function getAllMp()
    {
        $query = "SELECT id, firstname, lastname FROM members";
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
}
