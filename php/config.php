<?php
session_start();

define('APPLOCATION', '/huddland-parliament/');
define('LOGINPAGE', '/huddland-parliament/login.php');

include('class/user-class.php');
include('class/parliament-class.php');
include('class/db-class.php');
include('class/validate-class.php');
include('class/render-class.php');
