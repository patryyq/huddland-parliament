<?php
header('Content-type: text/html; charset=utf-8');

$headerCookie = $_COOKIE['header'] ?? 1;
if ($headerCookie == 0) {
    $header = 'class="none"';
    $button = 'class="hidden"';
} else {
    $header = 'class="flex"';
    $button = 'class="visible"';
}
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
                <?php echo '<div style="padding:1em">Hello <b>' . $user->name . ($user->admin ? '</b> (admin)</div><a href="manage.php">
                        <div class="menuElement">Manage Page</div>
                    </a>' : '</b></div>');  ?>
            </div>
            <div id="menuItems" class="flex">
                <a href="?logout">
                    <div class="menuElement">MPs</div>
                </a>
                <a href="?logout">
                    <div class="menuElement">Parties</div>
                </a>
                <a href="?logout">
                    <div class="menuElement">Logout</div>
                </a>
            </div>
        </div>
    </nav>
    <header id="header" <?php echo $header; ?>>
        <div class="header">
            <div class="headerText">
                <h1>Huddland Parliament</h1>
                The Huddland Parliament has two Houses that work on behalf of Huddland citizens to check and challenge the work of Government, make and shape effective laws, and debate/make decisions on the big issues of the day.
            </div>
        </div>
    </header>
    <div id="xButton" <?php echo  $button; ?>></div>