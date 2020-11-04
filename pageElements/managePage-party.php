<?php

?>
<div id="parties" class="manageTitle">Party</div>
<div class="manageContent none">
    <div id="addParty" class="flex" style="justify-content:space-between">
        <p>New <b>party</b> details:</p>
        <form method="POST" action="" class="flexForm">
            <div style="width:48%">
                <label for="partyName">Name:</label><br>
                <input type="text" placeholder="Party name" name="partyName" value="<?php echo $_SESSION['partyName'] ?? false; ?>">
            </div>
            <div style="width:48%">
                <label for="dateOfFoundation">Date of foundation:</label><br>
                <input type="number" placeholder="YYYY" name="dateOfFoundation" value="<?php echo $_SESSION['dateOfFoundation'] ?? false; ?>">
            </div>
            <div style="width:48%;position:relative">
                <label for="principalColour">Principal colour:</label><br>
                <input id="principalColour" autocomplete="off" type="text" placeholder="Colour name" name="principalColour" value="<?php echo $_SESSION['principalColour'] ?? false; ?>" style="margin-bottom:0;">
                <select id="colours" multiple style="display:none" size="10">
                    <?php
                    $parliament->coloursDropdown(); ?>
                </select>
            </div>
            <p style="margin-top: 3em;">Do you want to add a new <b>party</b>?</p>
            <div style="width:48%"> <input type="submit" name="addPartyButton" value="Add party"></div>
        </form>
    </div>
</div>
<script>
    let colours = document.getElementById('colours');
    let input = document.getElementById('principalColour');
    let select = document.getElementById('colours');
    let options = colours.getElementsByTagName('option');
    let body = document.getElementsByTagName('body')[0];
    let count = 0;
    searchColour = function() {
        count = 0;
        select.style.display = 'none';
        for (let i = 0; i < options.length; i++) {
            if (options[i].innerText.includes(input.value) && input.value.length > 0) {
                options[i].style.display = 'block';
                count++;
            } else if (input.value.length == 0) {
                options[i].style.display = 'block';
            } else {
                options[i].style.display = 'none';
            }
            // if (input.value === options[i].innerText) {
            //     select.style.display = 'none';
            // }
        }
        if (count === 0) {
            select.style.display = 'none';
        } else if (count < 10) {
            select.style.display = 'block';
            select.setAttribute('size', count);
            select.style.overflow = 'hidden';

        } else {
            select.style.display = 'block';
            select.setAttribute('size', 10);
            select.style.overflow = 'auto';

        }
    };

    openSelect = function() {
        select.style.display = 'block';
    }

    input.addEventListener('focus', openSelect);
    input.addEventListener('keyup', searchColour);
    body.addEventListener('click', function(event) {
        if (!event.target.classList.contains('colourOption') &&
            event.target.getAttribute('id') != 'principalColour') {
            select.style.display = 'none';
        }
    })
    select.addEventListener('click', function(event) {
        if (event.target.classList.contains('colourOption')) {
            input.value = event.target.innerText;
            select.style.display = 'none';
        }
    });
</script>