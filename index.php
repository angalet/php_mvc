<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Страница входа</title>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['NAME'])) {
    ?>
    <form method="post" action="">
        <p>Авторизоваться <input type="radio" checked name="auth_reg" value="auth" />
        Зарегистрироваться <input type="radio" name="auth_reg" value="reg" /></p>
        <p><input type="text" name="login" value="" /> Логин</p>
        <p><input type="text" name="pass" value="" /> Пароль</p>
        <p><input type="submit" name="reg" value="OK" /></p>
    </form>
<?php 
}
if (isset($_SESSION['NAME'])) {
    echo $_SESSION['NAME']?>
    
    <form method="post">
        <p><input type="submit" name="logout" value="Выйти" /></p>
    </form>

    <p>Вы авторизовались, <b>"<?php  echo $_SESSION['NAME'] ?>"</b></b><br>
    <h1>Список задач пользователя "<?php echo $_SESSION['NAME']?>"</h1><br>

    <form method="post">
    <p><input type="text" name="task" value="" /> Введите название дела и нажмите добавить</p>
    <p>Добавить дело <input type="submit" name="addtask" value="OK" /></p>
    </form>
    <form method='post'>
        <table width="80%" border="1" >
            <tr>
                <th width='30'>удалить</th>
                <th>Дело</th>
                <th>Когда</th>
                <th>выполнено/<br>невыполнено</th>
                <th>Исполнитель</th>
            </tr>

<?php 
}
    include 'code.php';
?>
</body>
</html>