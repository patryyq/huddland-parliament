<?php
// If cookie 'filters' is set to 0 and no parameters in URL,
// set transition:none in searchBar, so it hide instantly - 
// rather than after 350ms. It improves a 'feel' of the page.
(isset($_COOKIE['filters']) &&
    $_COOKIE['filters'] === '0' &&
    !isset($_GET['MPname']) &&
    !isset($_GET['interest']) &&
    !isset($_GET['party']) &&
    !isset($_GET['constituency'])) ?
    $transition = 'transition:none' :
    $transition = '';
?>
<main class="flex" style="align-content: flex-start;">
    <div id="searchBar" class="flex">
        <div style="width: 100%">
            <div id="showHideFilters" class="showHideFilters">Hide Filters</div>
        </div>
        <div id="searchInputsWrapper" class="flex vis" style="margin-top: 0.8em;<?php echo $transition; ?>">
            <div class="inputWrapper"><label for="MPname">MP</label>
                <input type="text" autocomplete="off" name="MPname" id="MPname">
            </div>
            <div class="inputWrapper"><label for="party">party</label>
                <?php echo $render->renderPartiesList(); ?> </div>
            <div class="inputWrapper"><label for="constituency">constituency</label>
                <?php echo $render->renderConstituenciesList(); ?></div>
            <div class="inputWrapper"><label for="interest">interest</label>
                <?php echo $render->renderInterests('list'); ?></div>
            <input type="submit" name="searchButton" id="searchButton" value="Search">
        </div>
    </div>
    <div id="filters">
        Filters:
        <div id="filterMP" class="filter" style="display:none">
            <div class="filterX">x</div>MP name
        </div>
        <div id="filterParty" class="filter" style="display:none">
            <div class="filterX">x</div>Party
        </div>
        <div id="filterConstituency" class="filter" style="display:none">
            <div class="filterX">x</div>Constituency
        </div>
        <div id="filterInterest" class="filter" style="display:none">
            <div class="filterX">x</div>Interest
        </div>
        <div id="filterNone" class="filter" style="display:inline-block">
            None
        </div>
    </div>
    <div id="browseText">Huddland Parliament MPs:</div>
    <div id="browseResults" class="browseResults">
        <?php
        echo $render->renderMpList(); ?>
    </div>
</main>
<script src="js/search.js"></script>