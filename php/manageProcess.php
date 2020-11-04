<?php
include('config.php');

$user = new user();
$user->admin ? true : header('Location: ' . LOGINPAGE);
$db = new db();


//header('Location: ../manage.php?error');
// echo var_dump($_SESSION['validError']);
