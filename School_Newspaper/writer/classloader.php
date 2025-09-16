<?php  
require_once 'classes/Article.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/category.php';

$databaseObj= new Database();
$userObj = new User();
$articleObj = new Article();

$userObj->startSession();
?>