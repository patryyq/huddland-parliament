<?php
// This file is used to render HTML content for "login.php" page
//
//
//
// 
if ($user->isLoggedIn()) {
    header('Location: ' . APPLOCATION);
} else if (isset($_POST['email']) || isset($_POST['password'])) {
    $user->logIn() ? header('Location: ' . APPLOCATION) : false;
}
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

<body style="justify-content:center;flex-wrap: wrap;">
    <div id="loginForm" class="flex">
        <h1>Huddland<br>Parliament</h1>
        <form method="POST" action="" class="flex">
            <?php echo $user->getError(); ?>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user->email; ?>">
            <input type="text" name="password" placeholder="Password">
            <input type="submit" name="submit" value="Sign in">
            <!-- <div style="border-top:1px solid #6379aa;width:50%;padding:20px 10px; margin-top:20px;text-align:left;box-sizing:border-box">
                Don't have account?<br><a href="#">Sign up</a>
            </div>
            <div style="width:50%;padding:20px 10px;border-top:1px solid #6379aa; margin-top:20px;text-align:right;box-sizing:border-box">
                Forgot password?<br><a href="#">Reset password</a>
            </div> -->
        </form>
    </div>

    <!-- prevent form resubmission when page is refreshed; not server-side solution but I believe it's good enough for my needs
source: https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr/16334537 -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>