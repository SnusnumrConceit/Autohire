<?php
if ($_COOKIE['Account'] ?? '') {
    foreach ($_COOKIE['Account'] as $key => $value) {
        $id = $key;
        $val = $value;
    }
    setcookie("Account[{$id}]", $val, time() -3600, '/');
    session_start();
} else {
     session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (($_POST['login'] ?? '') && ($_POST['pass'] ?? '')) {
        $login = $_POST['login'];
        $password = $_POST['pass'];
        if (md5($login) === md5('admin') && password_verify($password, password_hash('admin', PASSWORD_DEFAULT))) {
            
            $_SESSION['name'] = 'admin';
            header('location: Admin/admin.php');
        } else {
            echo("Вы ввели неверный логин и пароль!");    
        }
    } else {
        echo("Вы не ввели логин и пароль!");
    }
    
} else {
    http_response_code(404);
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div>
            <form method="POST">
                <div>
                    <label for="login">Логин</label>
                    <input type="text" name="login">
                </div>
                <div>
                    <label for="pass">Пароль</label>
                    <input type="password" name="pass">
                </div>
                <input type="submit"value="Войти">
            </form>
        </div>
    </body>
</html>