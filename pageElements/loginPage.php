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

<body style="justify-content:center;flex-wrap: wrap;">
    <div id="loginForm" class="flex">
        <h1>Huddland<br>Parliament</h1>
        <form method="POST" action="" class="flex">
            <?php echo $user->renderLogInError(); ?>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user->email; ?>">
            <input type="text" name="password" id="password" placeholder="Password">
            <input type="submit" name="submit" value="Sign in">
        </form>
    </div>

    <!-- prevent form resubmission when page is refreshed; not server-side solution but I believe it's good enough for my needs
source: https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr/16334537 -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Password field is 'text' type initially, to get around Chrome's autocomplete=off thing.
        // On password input field 'focus', change the type to 'password'.
        function changeInputTextToPassword(event) {
            event.path[0].type = 'password';
        }

        const passwordInputField = document.getElementById('password');
        passwordInputField.addEventListener('focus', changeInputTextToPassword)
    </script>
</body>

</html>