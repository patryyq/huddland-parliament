<?php

?>
<div id="mp" class="manageTitle">MP</div>
<div class="manageContent">
    <div id="addMp" class="flex toggle-content">
        <p>New <b>MP</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div class="inputWrapper">
                <label for="name">first name</label>
                <input type="text" name="firstname" value="<?php echo $_SESSION['firstname'] ?? false; ?>">
            </div>
            <div class="inputWrapper">
                <label for="name">last name</label>
                <input type="text" name="lastname" value="<?php echo $_SESSION['lastname'] ?? false; ?>">
            </div>
            <div class="inputWrapper">
                <label for="dateOfBirth">date of birth</label>
                <input type="date" name="dateOfBirth" value="<?php echo $_SESSION['dateOfBirth'] ?? false; ?>"></div>
            <div class="inputWrapper"><label for="party">party</label>
                <?php echo $parliament->displayPartiesList(''); ?> </div>
            <div class="inputWrapper"><label for="constituency">constituency</label>
                <?php echo $parliament->displayConstituenciesList(''); ?></div>
            <p>Interests:</p>
            <div style="width:100%" id="interestsBoxes" class="flex">
                <?php echo $parliament->displayInterests('checkbox'); ?>
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>MP</b>?</p>
            <div class="inputWrapper"> <input type="submit" name="addMpButton" value="Add MP"></div>
        </form>
    </div>
</div>
<?php
