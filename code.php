<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$pdo = new PDO("mysql:host=localhost;dbname=netology01; charset=utf8","admin","1qa2ws3ed");
//авторизация
if (isset($_POST['auth_reg']) and $_POST['auth_reg']=='auth'){
include 'auth.php';
}

//регистрация
if (isset($_POST['auth_reg']) and $_POST['auth_reg']=='reg'){
    include 'reg.php';
}

//задачи юзера
if (isset($_SESSION['NAME'])){
    include 'user_tasks.php';
}
?>