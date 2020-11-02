<?php
include('config.php');
$user = new user();
$user->admin ? true : header('Location: ' . LOGINPAGE);
$mp = new mp();

$amendMp = $_POST['amendMpButton'] ?? false;
$removeMp = $_POST['removeMpButton'] ?? false;
$addMp = $_POST['addMpButton'] ?? false;

// PARTY
$amendParty = $_POST['amendPartyButton'] ?? false;
$removeParty = $_POST['removePartyButton'] ?? false;
$addParty = $_POST['addPartyButton'] ?? false;

if ($amendMp) {

    $mp->amendMP();
} else if ($removeMp) {
    $mp->removeMP();
    // return true;
} else if ($addMp) {
    echo var_dump($addMp);
} else if ($addParty) {
    echo var_dump($addParty);
} else if ($removeParty) {
    echo var_dump($removeParty);
} else if ($amendParty) {
    echo var_dump($amendParty);
}
//header('Location: ../manage.php?error');
