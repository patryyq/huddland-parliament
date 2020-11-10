<?php
include('php/config.php');
$user = new user();
$parliament = new parliament();
$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);
if (!isset($_GET['mpID']) || !is_numeric($_GET['mpID'])) {
    header('Location: ' . APPLOCATION);
}
include('pageElements/header.php');
include('pageElements/mpPage.php');
include('pageElements/footer.php');
