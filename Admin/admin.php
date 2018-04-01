<?php
    /*НОВЫЙ ФУНКЦИОНАЛ
    session_start();
    if ($_SESSION ?? '') {
        if ($_SESSION['name'] === 'admin') {*/
print <<<ADMIN
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Админ-панель "АвтоПрокат"</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="Styles/admin.css">
    </head>
    <body>
        <div class="container">
            <div class="center-block">            
            <div class="row">
                <a class="col-sm-12 category" href="Products.php">
                    <span>Автомобили</span>
                </a>    
            </div>
            <div class="row">
                <a class="col-sm-3 col-xs-2 category" href="Brands.php">
                    <span>Марки</span>
                </a>
                <a class="col-sm-3 col-xs-2 category" href="Bodies.php">
                    <span>Кузовы</span>
                </a>
                <a class="col-sm-3 col-xs-2 category" href="Models.php">
                    <span>Модели</span>
                </a>    
            </div>
            <div class="row">
                <a class="col-sm-3 col-xs-2 category" href="Orders.php">
                    <span>Заказы</span>
                </a>
                <a class="col-sm-3 col-xs-2 category" href="Users.php">
                    <span>Пользователи</span>
                </a>
                <a class="col-sm-3 col-xs-2 category" href="Options.php">
                    <span>Опции</span>
                </a>    
            </div>
            </div>
        </div>
    </body>
</html>
ADMIN;
    /*КОНЕЦ ПРОВЕРОК
    } else {
        header('location: ../enter.php');
    }
} else {
    header('location: ../enter.php');
}*/
?>