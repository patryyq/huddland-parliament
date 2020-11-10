<?php
include('php/config.php');
$user = new user();

if (null !== (file_get_contents('php://input')) && $user->isLoggedIn()) {
    $parliament = new parliament();
    $postData = file_get_contents('php://input');

    // search function and echo json response
    if ($response = $parliament->search($postData)) {
        echo json_encode($response);
    }
} else {
    header('Location: ' . LOGINPAGE);
}
