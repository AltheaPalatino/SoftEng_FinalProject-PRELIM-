<?php  
require_once 'classes/Database.php';
require_once 'classes/Article.php';
require_once 'classes/User.php';
require_once 'classes/Category.php';

$db = new Database();
$pdo = $db->getConnection(); 

$articleObj = new Article($pdo);
$userObj = new User($pdo);
$categoryObj = new Category($pdo);
?>
