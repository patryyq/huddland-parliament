<?php
// This file is used to render HTML content for "mp.php" page
//
//
//
// variables to hold all MP details
$mpDetails = $parliament->getMpDetails()[0];
$firstName = $mpDetails['firstname'];
$lastName = $mpDetails['lastname'];
$dateOfBirth = $mpDetails['date_of_birth'];
$partyName = $mpDetails['name'];
$dateOfFoundation = date_format(date_create($mpDetails['date_of_foundation']), 'd/m/Y');;
$colour =  $mpDetails['principal_colour'];
$region =  $mpDetails['region'];
$electorate =  $mpDetails['electorate'];
$age = $parliament->getAge($dateOfBirth);
$formatedDoB = date_format(date_create($dateOfBirth), 'd/m/Y');
$interests = $mpDetails['interests'];
?>
<main>
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
            echo 'Interests: ' .  $interests . '<br>';
            ?>
        </div>


    </div>
</main>