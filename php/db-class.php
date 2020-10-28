<?php

class db
{

    private $dbHost = 'localhost'; // database host
    private $dbUser = 'root'; // database user
    private $dbPass = ''; // database password
    private $dbName = 'huddland-parliament'; // database name
    private $conn; // variable to hold connection

    function __construct()
    {
        // on object initialisation, connect to db; the main purpouse of this class
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
    // simplifie/speed up databse queries
    //
    // if no $parameters provided, execute without params
    public function dbQuery($query, $parameters = false)
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
}
