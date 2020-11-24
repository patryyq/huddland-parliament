<?php
include('php/config.php');
$user = new user();
$parliament = new parliament();
$validate = new validate();

$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);

if (!isset($_GET['mpID']) || !is_numeric($_GET['mpID'])) header('Location: ' . APPLOCATION);

include('php/pageElements/header.php');
include('php/pageElements/mpPage.php');
include('php/pageElements/footer.php');
