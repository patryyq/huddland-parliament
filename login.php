<?php
include('php/config.php');

$user = new user();
$parliament = new parliament();

if ($user->isLoggedIn()) {
    header('Location: ' . APPLOCATION);
} else if (isset($_POST['email']) || isset($_POST['password'])) {
    $user->logIn() ? header('Location: ' . APPLOCATION) : false;
}

include('pageElements/loginPage.php');
