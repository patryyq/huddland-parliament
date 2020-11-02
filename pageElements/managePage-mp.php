<?php
// variables to hold all of selected MP details
$mpDetails = $mp->getMpDetails()[0];
if ($mpDetails['firstname'] != NULL) {
    $firstName = $mpDetails['firstname'];
    $lastName = $mpDetails['lastname'];
    $dateOfBirth = $mpDetails['date_of_birth'];
    $partyName = $mpDetails['name'];
    $dateOfFoundation = date_format(date_create($mpDetails['date_of_foundation']), 'd/m/Y');;
    $colour =  $mpDetails['principal_colour'];
    $region =  $mpDetails['region'];
    $electorate =  $mpDetails['electorate'];
    $age = $mp->getAge($dateOfBirth);
    $formatedDoB = date_format(date_create($dateOfBirth), 'd/m/Y');
} ?>
<div id="mp" class="manageTitle">MPs</div>
<div class="manageContent none">
    <div id="mpButtons" class="flex" style="width:100%;justify-content:space-between;">
        <p>Choose from the following actions:</p>
        <div class="actionButton">Amend</div>
        <div class="actionButton">Remove</div>
        <div class="actionButton">Add</div>
    </div>
    <div id="amendMp" class="none" style="justify-content:space-between">
        <p>Select <b>MP</b> to <b>amend</b> from the dropdown menu:</p>
        <?php
        echo $mp->displayMpSelection('amend');
        if ($mpDetails['firstname'] ?? false) {
            echo '<form method="POST" action="php/manageProcess.php" class="flexForm"><div style="width:48%"><label for="name">First name:</label><br>
                 <input type="text" name="firstname" value="' . $firstName . '"></div>';
            echo '<div style="width:48%"><label for="lastname">Last name:</label><br>
                 <input type="text" name="lastname" value="' . $lastName . '"></div>';
            echo '<div style="width:48%"><label for="dateOfBirth">Date of birth:</label><br>
                  <input type="date" name="dateOfBirth" value="' . $dateOfBirth . '"></div>';
            echo '<div style="width:48%"><label for="party">Party:</label><br>'
                . $mp->displayPartiesList('amendMp') . '</div>';
            echo '<div style="width:48%"><label for="constituency">Constituency:</label><br>' . $mp->displayConstituenciesList('amendMp') . '</div>';
            echo '<div style="width:100%" class="flex"><p>Interests:</p>' . $mp->displayInterestsList() . '</div>';
            echo '<p>Do you want to amend <b>' . $firstName . ' ' . $lastName . '</b>?</p><div style="width:48%">
            <input type="submit" name="amendMpButton" value="Amend MP"></div></form>';
        }
        ?>
    </div>
    <div id="removeMp" class="none" style="justify-content:space-between">
        <p>Select <b>MP</b> to <b>remove</b>:</p>
        <?php
        echo $mp->displayMpSelection('remove');
        if ($mpDetails['firstname'] ?? false) {
            echo '<p>Do you want to remove <b>' . $firstName . ' ' . $lastName . '</b>?</p>
            <div style="width:48%">
                <form method="POST" action="php/manageProcess.php" class="flexForm">
                    <input type="submit" name="removeMpButton" value="Remove MP">
                </form>
            </div>';
        }
        ?>
    </div>
    <div id="addMp" class="none" style="justify-content:space-between">
        <p><b>Add</b> a new <b>MP</b>:</p>
        <form method="POST" action="php/manageProcess.php" class="flexForm">
            <div style="width:48%">
                <label for="name">First name:</label><br>
                <input type="text" name="firstname" value="">
            </div>
            <div style="width:48%">
                <label for="name">Last name:</label><br>
                <input type="text" name="lastname" value="">
            </div>
            <div style="width:48%">
                <label for="dateOfBirth">Date of birth:</label><br>
                <input type="date" name="dateOfBirth" value=""></div>
            <div style="width:48%"><label for="party">Party:</label><br>
                <?php echo $mp->displayPartiesList('addMp'); ?> </div>
            <div style="width:48%"><label for="constituency">Constituency:</label><br>
                <?php echo $mp->displayConstituenciesList('addMp'); ?></div>
            <div style="width:100%" class="flex">
                <p>Interests:</p><?php echo $mp->displayInterestsList(false); ?>
            </div>
            <p>Do you want to <b>add</b> a <b>new MP</b>?</p>
            <div style="width:48%"> <input type="submit" name="addMpButton" value="Add MP"></div>
        </form>
    </div>
</div>