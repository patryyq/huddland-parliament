<?php
if ($user->isLoggedIn()) {
    header('Location: ' . APPLOCATION);
} else if (isset($_POST['email']) || isset($_POST['password'])) {
    $user->logIn() ? header('Location: ' . APPLOCATION) : FALSE;
} ?>
<main>
    <div id="loginForm">
        <form method="POST" action="">
            <?php echo $user->getError(); ?>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user->email; ?>">
            <input type="text" name="password" placeholder="Password">
            <input type="submit" name="submit">
        </form>
    </div>
</main>