<?php

?>
<div id="constituencyTab" class="manageTitle">Constituency</div>
<div class="manageContent">
    <div id="addConstituency" class="flex toggle-content">
        <p>New <b>constituency</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div class="inputWrapper">
                <label for="interestName">region</label>
                <input type="text" autocomplete="off" name="constituencyRegion" value="<?php echo $_SESSION['constituencyRegion'] ?? false; ?>">
            </div>
            <div class="inputWrapper">
                <label for="electorate">electorate</label>
                <input type="number" name="electorate" value="<?php echo $_SESSION['electorate'] ?? false; ?>">
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>constituency</b>?</p>
            <div class="inputWrapper"> <input type="submit" name="addConstituencyButton" value="Add constituency"></div>
        </form>
    </div>
</div>