<footer>
    <div class="footer">© Huddland Parliament 2020<br>Patryk Świder<br>Web Programming with Cyber Security (2nd year)<br>University of Huddersfield</div>
</footer>

<!-- prevent form resubmission when page is refreshed; not server-side solution but I believe it's good enough for my needs
source: https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr/16334537 -->
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</body>

</html>