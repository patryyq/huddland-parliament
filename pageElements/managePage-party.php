<?php

?>
<div id="parties" class="manageTitle">Party</div>
<div class="manageContent">
    <div id="addParty" class="flex toggle-content">
        <p>New <b>party</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div class="inputWrapper">
                <label for="partyName">name</label>
                <input type="text" name="partyName" value="<?php echo $_SESSION['partyName'] ?? false; ?>">
            </div>
            <div class="inputWrapper">
                <label for="dateOfFoundation">year of foundation</label>
                <input type="number" placeholder="YYYY" name="dateOfFoundation" value="<?php echo $_SESSION['dateOfFoundation'] ?? false; ?>">
            </div>
            <div class="inputWrapper">
                <label for="principalColour">colour</label>
                <input id="principalColour" autocomplete="off" type="text" name="principalColour" value="<?php echo $_SESSION['principalColour'] ?? false; ?>" style="margin-bottom:0;">
                <select id="colours" multiple style="display:none" size="10">
                    <?php
                    $parliament->coloursDropdown(); ?>
                </select>
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>party</b>?</p>
            <div class="inputWrapper"> <input type="submit" name="addPartyButton" value="Add party"></div>
        </form>
    </div>
</div>