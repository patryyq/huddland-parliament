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
        isset($_GET['logout']) ? $this->logOut() : FALSE;
        $this->password = $_POST['password'] ?? FALSE;
        $this->email = $_POST['email'] ?? FALSE;
        $this->name = $_SESSION['name'] ?? FALSE;
        $this->admin = $_SESSION['admin'] ?? FALSE;
        $this->db = new db();
    }

    // check if user is logged in
    public function isLoggedIn()
    {
        return $_SESSION['isLoggedIn'] ?? FALSE;
    }

    // log in
    public function logIn()
    {
        // get all details to log in based on provided email
        if ($result = $this->getDetailsToLogIn()) {
            // check if passwords match
            if ($this->verifyHash($this->password, $result[0]['password'])) {
                // set isLoggedIn session variable to true, also set admin variable based on 'role' from db
                $_SESSION['isLoggedIn'] = TRUE;
                $_SESSION['name'] = $result[0]['name'];
                ($result[0]['role'] == '2') ? $_SESSION['admin'] = TRUE : $_SESSION['admin'] = FALSE;
                return TRUE;
            } else {
                $this->error = "Email or password doesn't match.<br>";
                return FALSE;
            }
        }
        $this->error = "Email or password doesn't match.<br>";
    }

    public function logOut()
    {
        unset($_SESSION['isLoggedIn']);
        unset($_SESSION['name']);
        unset($_SESSION['admin']);
        session_destroy();
        // header to APPLOCATION, but will get redirected to login page anyways
        header('Location: ' . LOGINPAGE);
        exit();
    }

    // return errors from $this->error
    public function getError()
    {
        $this->error ?
            $error = '<div class="error">' . $this->error . '</div>' :
            $error = '';
        return $error;
    }

    // verify if provided, hashed password matches hash from DB
    private function verifyHash($password, $hashFromDb)
    {
        return password_verify($password, $hashFromDb) ? TRUE : FALSE;
    }

    // get the details needed to log in based on provided email; return false if no email marched
    private function getDetailsToLogIn()
    {
        $query = "SELECT name, password, email, id, role FROM users WHERE email = ?";
        $result = $this->db->dbQuery($query, array($this->email));
        return $result ? $result : FALSE;
    }
}
