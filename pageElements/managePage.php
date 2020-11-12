<?php
// This file is used to render HTML content for "manage.php" page
//
//
// could use '$_GET ?? false' synthax but in my case it returns false cause value is empty
// isset() returns true even if the value is empty
$mpGET = isset($_GET['mp']) ? true : false;
$partyGET = isset($_GET['party']) ? true : false;
$interestsGET = isset($_GET['interest']) ? true : false;
$constituencyGET = isset($_GET['constituency']) ? true : false;

?>
<main>
    <div id="manage" class="wrapper">
        <h1>Add content to the page</h1>
        <p style="margin-bottom:2em;">
            <b><?php echo $user->name; ?></b>, as a admin, you can <b>add</b> content to the Huddland Parliament website.</p>
        <?php
        $parliament->displayMessage();
        $mpGET ? $parliament->displayError() : false;
        ?>
        <div id="MPmanage" class="manage">
            <?php include('managePage-mp.php'); ?>
        </div>
        <?php
        $partyGET ? $parliament->displayError() : false;
        ?>
        <div id="PARTYmanage" class="manage">
            <?php include('managePage-party.php'); ?>
        </div>
        <?php
        $interestsGET ? $parliament->displayError() : false;
        ?>
        <div id="INTERESTSmanage" class="manage">
            <?php include('managePage-interests.php'); ?>
        </div>
        <?php
        $constituencyGET ? $parliament->displayError() : false;
        ?>
        <div id="CONSTITUENCYmanage" class="manage">
            <?php include('managePage-constituency.php'); ?>
        </div>
    </div>
</main>
<script src="js/manage.js"></script>
<?php
$parliament->unsetSession();
