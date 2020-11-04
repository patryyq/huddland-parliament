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

if ((isset($_GET['mp']) ||
        isset($_GET['party']) ||
        isset($_GET['interest']) ||
        isset($_GET['constituency'])) &&
    (!isset($_SESSION['confirmationMessage']) && !isset($_SESSION['validError']))
) {
    header('Location: manage.php');
}
// if (isset($_GET['party']) && (!isset($_SESSION['confirmationMessage']) && !isset($_SESSION['validError']))) {
//     header('Location: manage.php');
// }

// if (isset($_GET['interest']) && (!isset($_SESSION['confirmationMessage']) && !isset($_SESSION['validError']))) {
//     header('Location: manage.php');
// }

// if (isset($_GET['constituency']) && (!isset($_SESSION['confirmationMessage']) && !isset($_SESSION['validError']))) {
//     header('Location: manage.php');
// }

include('pageElements/header.php');
include('pageElements/managePage.php');
include('pageElements/footer.php');

unset($_SESSION['validError']);
unset($_SESSION['confirmationMessage']);
