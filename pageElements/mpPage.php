<?php
// This file is used to render HTML content for "mp.php" page
//
//
//
// variables to hold all MP details
$mpDetails = $parliament->getMpDetails()[0];
if ($mpDetails['firstname'] == false) {
    header('Location: ' . APPLOCATION);
}
$firstName = $mpDetails['firstname'];
$lastName = $mpDetails['lastname'];
$dateOfBirth = $mpDetails['date_of_birth'];
$partyName = $mpDetails['name'];
$dateOfFoundation = $mpDetails['date_of_foundation'];
$colour =  str_replace(' ', '', $mpDetails['principal_colour']); // remove spaces in colour names
$region =  $mpDetails['region'];
$electorate =  $mpDetails['electorate'];
$age = $parliament->getAge($dateOfBirth);
$formatedDoB = date_format(date_create($dateOfBirth), 'd/m/Y');
$interests = $mpDetails['interests'];
?>
<style>
    .mpSingleDetail {
        border-left: 7px solid <?php echo $colour; ?>;
    }

    #mpName {
        border-left: 7px solid <?php echo $colour; ?>;
    }

    .randomFace {
        border: 7px solid <?php echo $colour; ?>;
    }

    .topBlock {
        background: <?php echo $colour; ?>;
    }
</style>
<main>
    <div class="topBlock"></div>
    <div id="mpDetails" style="margin-bottom:3em">
        <div class="mpNameWrapper flex">
            <div id="randomFace">
                <!-- info about random face in js/mp.js -->
                <img class="randomFace" src="img/api_broken.jpg">
            </div>
            <div id="mpName">
                <h1><?php
                    echo $firstName . ' ';
                    echo $lastName;
                    ?></h1>
                <i>
                    <div id="randomQuote">
                        <p class="randomQuote">"Here should be random quote, but something went wrong."</p>
                    </div>
                </i>
            </div>
        </div>
        <div class="mpDetails flex">
            <div class="mpSingleDetail">Date of birth: <b><?php echo $formatedDoB; ?></b></div>
            <div class="mpSingleDetail">Age: <b id="age"><?php echo $age; ?></b></div>
            <div class="mpSingleDetail">Party: <b><?php echo $partyName; ?></b></div>
            <div class="mpSingleDetail">Year of foundation: <b><?php echo $dateOfFoundation; ?></b></div>
            <div class="mpSingleDetail">Constituency: <b><?php echo $region; ?></b></div>
            <div class="mpSingleDetail">Electorate: <b><?php echo $electorate; ?></b></div>
            <div class="mpSingleDetail">Interests: <b><?php echo $interests; ?></b></div>
        </div>
    </div>
</main>
<script src="js/mp.js"></script>