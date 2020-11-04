<?php

?>
<div id="constituencyTab" class="manageTitle">Constituency</div>
<div class="manageContent none">
    <div id="addConstituency" class="flex" style="justify-content:space-between">
        <p>New <b>constituency</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div style="width:48%">
                <label for="interestName">Region:</label><br>
                <input type="text" placeholder="Region name" name="constituencyRegion" value="<?php echo $_SESSION['constituencyRegion'] ?? false; ?>">
            </div>
            <div style="width:48%">
                <label for="electorate">Electorate:</label><br>
                <input type="number" placeholder="Electorate number" name="electorate" value="<?php echo $_SESSION['electorate'] ?? false; ?>">
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>constituency</b>?</p>
            <div style="width:48%"> <input type="submit" name="addConstituencyButton" value="Add constituency"></div>
        </form>
    </div>
</div>