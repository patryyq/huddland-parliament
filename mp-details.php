<?php
include('php/config.php');
$user = new user();
$parliament = new parliament();
$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);

include('pageElements/header.php');
include('pageElements/mp-detailsPage.php');
include('pageElements/footer.php');
