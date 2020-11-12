<?php

?>
<div id="interests" class="manageTitle">Interest</div>
<div class="manageContent">
    <div id="addInterest" class="flex toggle-content">
        <p>New <b>interest</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div class="inputWrapper">
                <label for="interestName">name</label>
                <input type="text" name="interestName" value="<?php echo $_SESSION['interestName'] ?? false; ?>">
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>interest</b>?</p>
            <div class="inputWrapper"> <input type="submit" name="addInterestButton" value="Add interest"></div>
        </form>
    </div>
</div>