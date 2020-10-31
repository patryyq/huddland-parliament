<?php
include('config.php');
$user = new user();
$user->admin ? true : header('Location: ' . LOGINPAGE);

$mp = new mp();
echo '<br>first ' . $_POST['firstname'];
echo '<br>last ' . $_POST['lastname'];
echo '<br>dob ' . $_POST['dateOfBirth'];
echo '<br>part ' . $_POST['party'];
echo '<br>const ' . $_POST['constituency'];
echo '<pre>';
echo var_dump($_POST['interests']);
echo '</pre>';
// MP
$amendMp = $_POST['amendMpButton'] ?? false;
$removeMp = $_POST['removeMpButton'] ?? false;
$addMp = $_POST['addMpButton'] ?? false;

// PARTY
$amendParty = $_POST['amendPartyButton'] ?? false;
$removeParty = $_POST['removePartyButton'] ?? false;
$addParty = $_POST['addPartyButton'] ?? false;

if ($amendMp) {
    echo '<br>' . $_SESSION['mpFirstName'];
    echo '<br>' . $_SESSION['mpLastName'];
    echo '<br>' . $_SESSION['mpDoB'];
    echo '<br>' . $_SESSION['mpPartyID'];
    echo '<br>' . $_SESSION['mpConstituencyID'];
    echo '<pre>';
    echo var_dump($_SESSION['mpInterestIDs']);
    echo '</pre>';
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
