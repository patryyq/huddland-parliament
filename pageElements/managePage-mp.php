<?php

?>
<div id="mp" class="manageTitle">MP</div>
<div class="manageContent">
    <div id="addMp" class="flex toggle-content">
        <p>New <b>MP</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div style="width:48%">
                <label for="name">First name:</label><br>
                <input type="text" placeholder="MP's first name" name="firstname" value="<?php echo $_SESSION['firstname'] ?? false; ?>">
            </div>
            <div style="width:48%">
                <label for="name">Last name:</label><br>
                <input type="text" placeholder="MP's last name" name="lastname" value="<?php echo $_SESSION['lastname'] ?? false; ?>">
            </div>
            <div style="width:48%">
                <label for="dateOfBirth">Date of birth:</label><br>
                <input type="date" name="dateOfBirth" value="<?php echo $_SESSION['dateOfBirth'] ?? false; ?>"></div>
            <div style="width:48%"><label for="party">Party:</label><br>
                <?php echo $parliament->displayPartiesList('Select party'); ?> </div>
            <div style="width:48%"><label for="constituency">Constituency:</label><br>
                <?php echo $parliament->displayConstituenciesList('Select constituency'); ?></div>
            <p>Interests:</p>
            <div style="width:100%" id="interestsBoxes" class="flex">
                <?php echo $parliament->displayInterests('checkbox'); ?>
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>MP</b>?</p>
            <div style="width:48%"> <input type="submit" name="addMpButton" value="Add MP"></div>
        </form>
    </div>
</div>
<?php
