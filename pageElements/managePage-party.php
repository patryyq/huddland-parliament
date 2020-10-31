<?php
// variables to hold all of selected PARTY details
$partyDetails = $mp->getPartyDetails()[0];
if ($partyDetails['name'] != NULL) {
    $name = $partyDetails['name'];
    $dateOfFoundation = $partyDetails['date_of_foundation'];
    $principalColour = $partyDetails['principal_colour'];
}
?>
<div id="parties" class="manageTitle">Parties</div>
<div class="manageContent none">
    <div id="partyButtons" class="flex" style="width:100%;justify-content:space-between;">
        <p>Choose from the following actions:</p>
        <div class="actionButton">Amend</div>
        <div class="actionButton">Remove</div>
        <div class="actionButton">Add</div>
    </div>
    <div id="amendParty" class="none" style="justify-content:space-between">
        <p>Select <b>PARTY</b> to <b>amend</b> from the dropdown menu:</p>
        <?php
        echo $mp->displayPartiesList('amendParty');
        if ($partyDetails['id'] != NULL) {
            echo '<form method="POST" action="php/manageProcess.php" class="flexForm"><div style="width:48%"><label for="name">Name:</label><br>
                 <input type="text" name="partyName" value="' . $name . '"></div>';
            echo '<div style="width:48%"><label for="dateOfFoundation">Date of foundation:</label><br>
                 <input type="number" name="dateOfFoundation" value="' . $dateOfFoundation . '"></div>';
            echo '<div style="width:48%"><label for="princiapColour">Principal colour:</label><br>
                  <input type="text" name="princiapColour" value="' . $principalColour . '"></div>';
            echo '<p>Do you want to amend <b>' . $name . '</b>?</p><div style="width:48%"><input type="submit" name="amendPartyButton" value="Amend PARTY"></div></form>';
        }
        ?>
    </div>
    <div id="removeParty" class="none" style="justify-content:space-between">
        <p>Select <b>PARTY</b> to <b>remove</b> from the dropdown menu:</p>
        <?php
        echo $mp->displayPartiesList('removeParty');
        if ($partyDetails['id'] != NULL) {
            echo '<p>Do you want to remove <b>' . $name . '</b>?</p><div style="width:48%">
            <form method="POST" action="php/manageProcess.php" class="flexForm"><input type="submit" name="removePartyButton" value="Remove PARTY"></form></div>';
        }
        ?>
    </div>
    <div id="addParty" class="none" style="justify-content:space-between">
        <p><b>Add</b> a new <b>PARTY</b>:</p>
        <div style="width:48%">
            <form method="POST" action="php/manageProcess.php" class="flexForm">
                <input type="submit" name="addPartyButton" value="Add PARTY">
            </form>
        </div>
    </div>
</div>