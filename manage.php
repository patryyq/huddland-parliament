<?php
include('php/config.php');
$user = new user();
$user->admin ? true : header('Location: ' . LOGINPAGE);
$parliament = new parliament();
$db = new db();

$addConstituency = $_POST['addConstituencyButton'] ?? false;
$addMp = $_POST['addMpButton'] ?? false;
$addInterest = $_POST['addInterestButton'] ?? false;
$addParty = $_POST['addPartyButton'] ?? false;

// if one of ADD buttons clicked, perform desired action;
// die() to stop executing rest of this page - each of the functions takes care of everything;
if ($addMp) {
    $db->addMP();
    die();
} else if ($addParty) {
    $db->addPARTY();
    die();
} else if ($addInterest) {
    $db->addINTEREST();
    die();
} else if ($addConstituency) {
    $db->addCONSTITUENCY();
    die();
}

// if no message or error, and $_GET is set (someone must have put it manually or page refreshed)
// then remove the parameter (header location) because JS will generate error trying to get
// element (message/error div) which doesnt exist;
if ((isset($_GET['mp']) ||
        isset($_GET['party']) ||
        isset($_GET['interest']) ||
        isset($_GET['constituency'])) &&
    (!isset($_SESSION['confirmationMessage']) && !isset($_SESSION['errorMessage']))
) {
    header('Location: manage.php');
}

include('pageElements/header.php');
include('pageElements/managePage.php');
include('pageElements/footer.php');

unset($_SESSION['errorMessage']);
unset($_SESSION['confirmationMessage']);
