<?php
include('php/config.php');
$user = new user();
$mp = new mp();
$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);

include('pageElements/header.php');
include('pageElements/mp-detailsPage.php');
include('pageElements/footer.php');
