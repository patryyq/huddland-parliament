<?php

class user
{
    public $name;
    public $email;
    public $admin;
    private $error;
    private $password;
    private $db;

    function __construct()
    {
        isset($_GET['logout']) ? $this->logOut() : false;
        $this->password = $_POST['password'] ?? false;
        $this->email = $_POST['email'] ?? false;
        $this->name = $_SESSION['name'] ?? false;
        $this->admin = $_SESSION['admin'] ?? false;
        $this->db = new db();
    }

    public function isLoggedIn()
    {
        return $_SESSION['name'] ?? false;
    }

    public function logIn()
    {
        if ($result = $this->getDetailsToLogIn()) {
            if ($this->verifyPassword($this->password, $result[0]['password'])) {
                $_SESSION['name'] = $result[0]['name'];
                $_SESSION['admin'] = $result[0]['role'] == '2' ? true : false;
                return true;
            } else {
                $this->error = "Email or password doesn't match.";
                return false;
            }
        }
        $this->error = "Email or password doesn't match.";
    }

    public function logOut()
    {
        session_destroy();
        header('Location: ' . LOGINPAGE);
    }

    public function renderLogInError()
    {
        $this->error ?
            $error = '<div class="error">' . $this->error . '</div>' :
            $error = '';
        return $error;
    }

    private function verifyPassword($password, $hashFromDb)
    {
        return password_verify($password, $hashFromDb) ? true : false;
    }

    // get log in details based on provided email; return false if no email matched
    private function getDetailsToLogIn()
    {
        $query = "SELECT name, password, email, id, role FROM users WHERE email = ?";
        $result = $this->db->selectQuery($query, [$this->email]);
        return $result ? $result : false;
    }
}
