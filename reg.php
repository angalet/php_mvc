<?php
$data = [
        'login' => $_POST["login"]
    ];
    $user_from_base = "SELECT id,login,password FROM user WHERE login= :login";
    $stmt= $pdo->prepare($user_from_base);
    $stmt->execute($data);
    $login = $stmt->fetch();
    if ($login['login']) echo "Пользователь с логином ".$login['login']." уже существует! ";
    if ($_POST["login"] and $_POST["pass"] and !$login['login']){
        $data = [
            'login' => $_POST["login"],
            'password' => $_POST["pass"]
        ];
        $sql = "INSERT INTO user (login, password) VALUES (:login, :password)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        $data = [
            'login' => $_POST["login"]
        ];
        $user_from_base = "SELECT id,login,password FROM user WHERE login= :login";
        $stmt= $pdo->prepare($user_from_base);
        $stmt->execute($data);
        $login = $stmt->fetch();
        if ($login['login'] == $_POST["login"]){
            echo "Поздравляем ".$login['login'].", Вы зарегистрировались!";
        }
    
    }