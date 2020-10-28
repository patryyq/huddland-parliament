<?php
$mpDetails = $mp->getMpDetails()[0];
$firstName = $mpDetails['firstname'];
$lastName = $mpDetails['lastname'];
$dateOfBirth = $mpDetails['date_of_birth'];
$partyName = $mpDetails['name'];
$dateOfFoundation = date_format(date_create($mpDetails['date_of_foundation']), 'd/m/Y');;
$colour =  $mpDetails['principal_colour'];
$region =  $mpDetails['region'];
$electorate =  $mpDetails['electorate'];
$age = $mp->getMpAge($dateOfBirth);
$formatedDoB = date_format(date_create($dateOfBirth), 'd/m/Y');
?>
<main>
    <a href="?logout">Logout</a>
    <div id="mpDetails">
        <div class="mpName">
            <?php
            echo $firstName . ' ';
            echo $lastName;
            ?>
        </div>
        <div class="mpDetails">
            <?php
            echo 'Date of birth: ' . $formatedDoB . '<br>';
            echo 'Age: ' .  $age . '<br>';
            echo 'Party: ' . $partyName . '<br>';
            echo 'Date of foundation: ' .  $dateOfFoundation . '<br>';
            echo 'Principal colour: ' . $colour . '<br>';
            echo 'Region: ' .  $region . '<br>';
            echo 'Electorate: ' .  $electorate . '<br>';
            ?>
        </div>


    </div>
</main>