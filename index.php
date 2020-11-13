<?php
include('php/config.php');
$user = new user();
$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);
$parliament = new parliament();

include('pageElements/header.php');
include('pageElements/indexPage.php');
include('pageElements/footer.php');
