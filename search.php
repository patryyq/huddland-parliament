<?php
include('php/config.php');
$user = new user();

if (null !== (file_get_contents('php://input')) && $user->isLoggedIn()) {
    $parliament = new parliament();
    $POSTdata = file_get_contents('php://input');

    if ($responseToJS = $parliament->search($POSTdata)) echo json_encode($responseToJS);
} else {
    header('Location: ' . LOGINPAGE);
}
