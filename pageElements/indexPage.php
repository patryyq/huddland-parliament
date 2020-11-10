<main class="flex" style="align-content: flex-start;">
    <div id="searchBar" class="flex">
        <div class="inputWrapper"><label for="MPname">MP name</label>
            <input type="text" autocomplete="off" name="MPname" id="MPname"></div>
        <div class="inputWrapper"><label for="party">Party</label>
            <?php echo $parliament->displayPartiesList('', true); ?> </div>
        <div class="inputWrapper"><label for="constituency">Constituency</label>
            <?php echo $parliament->displayConstituenciesList('', true); ?></div>
        <div class="inputWrapper"><label for="interest">Interest</label>
            <?php echo $parliament->displayInterests('list'); ?></div>
        <input type="submit" name="searchButton" id="searchButton" value="Search" style="width:25%;">
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
    <div id="browseResults" class="browseResults">
        <?php $parliament->displayMpList(); ?>
    </div>
</main>
<script src="js/search.js"></script>