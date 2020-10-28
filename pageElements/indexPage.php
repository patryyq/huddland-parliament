<main>
    <a href="?logout">Logout</a>
    <?php
    echo 'Hello <b>' . $user->name . ($user->admin ? '</b> (admin)' : '</b>') . '!<br><br>';
    $mp->displayMpList();
    ?>
</main>