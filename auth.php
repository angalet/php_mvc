<?php
if (!isset($_SESSION['NAME']) and $_POST['auth_reg']=='auth') {
    $user_from_base = "SELECT id,login,password FROM user WHERE login= ?";
    $stmt= $pdo->prepare($user_from_base);
    $stmt->execute([$_POST['login']]);
    $login = $stmt->fetch();
    echo "<pre>";
    echo "</pre>";
    if ($_POST['login'] and $_POST['login']==$login["login"] and $_POST['pass']==$login["password"]){
        $_SESSION['NAME'] = $login["login"];
        $_SESSION['user_id'] = $login["id"];
        setcookie("user_name", $_POST['login']);
        setcookie("user_auth", "YES");
        echo "вы авторизовались ".$_SESSION['NAME'];
        header("Location: index.php"); 
    }
} 
if (!isset($_SESSION['NAME'])) {
    header('HTTP/1.0 401 Unauthorized');
    echo "вы не авторизовались";
    exit;
}