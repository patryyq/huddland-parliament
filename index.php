<?php
include('php/config.php');
$user = new user();
$render = new render();

$user->isLoggedIn() ? true : header('Location: ' . LOGINPAGE);

$parliament = new parliament();
include('php/pageElements/header.php');
include('php/pageElements/indexPage.php');
include('php/pageElements/footer.php');
