<?php

?>
<div id="interests" class="manageTitle">Interest</div>
<div class="manageContent none">
    <div id="addInterest" class="flex" style="justify-content:space-between">
        <p>New <b>interest</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div style="width:48%">
                <label for="interestName">Name:</label><br>
                <input type="text" placeholder="Interest name" name="interestName" value="<?php echo $_SESSION['interestName'] ?? false; ?>">
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>interest</b>?</p>
            <div style="width:48%"> <input type="submit" name="addInterestButton" value="Add interest"></div>
        </form>
    </div>
</div>