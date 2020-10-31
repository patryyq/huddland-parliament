<?php
// This file is used to render HTML content for "manage.php" page
//
//
//

// variables to hold all of selected PARTY details
?>
<main>
    <div id="manage" class="wrapper">
        <h1>Manage page content</h1>
        <p style="padding: 0 1em; box-sizing:border-box;">
            <b><?php echo $user->name; ?></b>, as a admin, you can <b>amend</b>, <b>remove</b> or <b>add</b> data to Huddland Parliament.</p>
        <div class="tutaj message">
            <!-- <p style="padding: 0 1em; box-sizing:border-box; color:red;">
                <b><?php echo $user->name; ?></b>, as a admin, you can <b>amend</b>, <b>remove</b> or <b>add</b> data to Huddland Parliament.</p> -->
        </div>
        <div id="MPmanage" class="manage">
            <?php include('managePage-mp.php'); ?>
        </div>
        <div id="PARTYmanage" class="manage">
            <?php include('managePage-party.php'); ?>
        </div>
        <div class="manage">
            <div id="interests" class="manageTitle">Interests</div>
            <div class="manageContent none">
                <p>Here you can add, remove and amend interests.</p>
            </div>
        </div>
        <div class="manage">
            <div id="constituencies" class="manageTitle">Constituencies</div>
            <div class="manageContent none">
                <p>Here you can add, remove and amend constituencies.</p>
            </div>
        </div>
    </div>
</main>
<script src="js/manage.js"></script>