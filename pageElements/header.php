<?php
header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <base href="http://localhost/huddland-parliament/" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/style.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <nav>
        <div id="menu" class="flex" style="justify-content:space-between;">
            <div class="flex">
                <?php echo '<div style="padding:1em">Hello <b>' . $user->name . ($user->admin ? '</b> (admin)</div>' : '</b></div>');  ?>
            </div>
            <div id="menuItems" class="flex">
                <?php echo ($user->admin ? '<a href="manage.php"><div class="menuElement">Add content</div></a>' : FALSE);  ?>
                <a href="">
                    <div class="menuElement">Parliament</div>
                </a>
                <a href="?logout">
                    <div class="menuElement">Logout</div>
                </a>
            </div>
            <div id="menuBurger">Menu</div>
        </div>
    </nav>
    <header id="header">
        <div id="toggleHeader" class="header vis">
            <div class="headerText">
                <h1>Huddland Parliament</h1>
                <div id="topDesc" style="height:auto;opacity:1;transition:unset">The Huddland Parliament has two Houses that work on behalf of Huddland citizens to check and challenge the work of Government, make and shape effective laws, and debate/make decisions on the big issues of the day.</div>
            </div>
        </div>
        <div id="xButton" class="visible"></div>
        <script src='js/general.js'></script>
    </header>