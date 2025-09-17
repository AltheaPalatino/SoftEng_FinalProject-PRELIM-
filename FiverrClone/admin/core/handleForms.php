<?php
require_once __DIR__ . "/../classes/User.php";
$user = new User();  

if (isset($_GET['logoutUserBtn'])) {
    $user->logout();  
    exit;
}

