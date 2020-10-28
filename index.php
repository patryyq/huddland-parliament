<?php
include('php/config.php');
include('pageElements/header.php');
$user = new user();
$mp = new mp();
$user->isLoggedIn() ? TRUE : header('Location: ' . LOGINPAGE);

include('pageElements/indexPage.php');
include('pageElements/footer.php');
