<?php
include('php/config.php');
$user = new user();
$mp = new mp();
$user->admin ? true : header('Location: ' . LOGINPAGE);

include('pageElements/header.php');
include('pageElements/managePage.php');
include('pageElements/footer.php');
