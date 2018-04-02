<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['order'] ?? '') {
            $inputData = json_decode($_POST['order']);            
            require_once '../Classes/Order.php';
            $order = new Order();
            if ($order->CheckData($inputData)) {
                $order = $order->SetData($inputData, $order);
                $order->CreateOrder($order);    
            }            
        } 
        elseif ($_POST['id'] ?? '') {
            $id = $_POST['id'];            
            require_once '../Classes/Order.php';
            $order = new Order();
            $order->DeleteOrder($id);
        } else {
            echo('Ничего не пришло');
        }        
    }
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['order'] ?? '') {
            $inputData = $_GET['order'];
print <<<ORDERS
<!DOCTYPE html>
<html>
    <head>
        <title>Заказы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div class="row">
            <div class="col-3">
                <button id="btn-open-create-order-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="find-order-container col">                
                <form method="GET" class="form-inline">
                    <input class="form-control col-sm-5" type="text" id="order" placeholder="Введите фамилию клиента">
                    <button id="btn-find-order" class="btn btn-primary col-form-label">Найти</button>
                </form>
            </div>
            </div>
            <div class="form-group create-order-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="user" class="col-sm-2 col-form-label">Пользователь</label>
ORDERS;
            require_once '../Classes/User.php';
            $user = new User();
            $users = $user->ShowUsers();
            if ($users) {
                $usersLength = count($users);
                print "<select id=\"user\" class=\"form-control col-sm-4\">";
                for ($i=0; $i < $usersLength; $i++) { 
                    print "<option value=\"{$users[$i]->id}\">{$users[$i]->Login} ({$users[$i]->LName} {$users[$i]->FName} {$users[$i]->MName})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте пользователей в разделе <a href='users.php'><strong>Пользователи</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group row\">
                   <label for=\"car\" class=\"col-form-label col-sm-2\">Автомобиль</label>";
                    

            require_once '../Classes/Product.php';
            $car = new Product();
            $cars = $car->ShowProducts();
            if ($cars) {
                print "<select id=\"car\" class=\"form-control col-sm-4\">";
                for ($i=0; $i < $carsLength; $i++) { 
                    print "<option value=\"{$cars[$i]->id}\">{$cars[$i]->Model} ({$cars[$i]->Type})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте автомобиль в разделе <a href='products.php'><strong>Автомобили</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group row\">
                    <label for=\"hours\" class=\"col-sm-2 col-form-label\">Количество часов</label>
                    <input class=\"form-control col-sm-4\" id=\"hours\">
                </div>
                <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <div>
                <h2>Заказы</h2>";
                require_once '../Classes/Order.php';
                $orders = new Order();
                $orders = $orders->FindOrder($inputData);
                if ($orders) {
                    $findlessLength = count($orders);
                    print "<table class=\"table table-bordered\">
                                        <thead>
                                            <th>id</th>
                                            <th>Логин</th>
                                            <th>ФИО</th>
                                            <th>Модель</th>
                                            <th>Кузов</th>
                                            <th>Цена</th>
                                            <th>Операции</th>
                                        </thead>
                                        <tbody>";
                    for ($i=0; $i < $findlessLength; $i++) { 
                        print "<tr>
                                    <td>{$orders[$i]->id}</td>
                                    <td>{$orders[$i]->Login}</td>
                                    <td>{$orders[$i]->User}</td>
                                    <td>{$orders[$i]->Model}</td>
                                    <td>{$orders[$i]->Type}</td>
                                    <td>{$orders[$i]->Price}</td>
                                    <td><button class=\"btn btn-danger\">Удалить</button></td>
                                </tr>";
                    }
                    print "</tbody>
                    </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/orders_scripts.js\"></script>      
    </body>
</html>";
                } else {
                    echo("<div>По запросу <i>{$inputData}</i> не найдено ни одного заказа</div>
                    </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/orders_scripts.js\"></script>      
    </body>
</html>");
                }
        }
    #####_____ОСНОВНАЯ VIEW________########
    else {
print <<<ORDERS
<!DOCTYPE html>
<html>
    <head>
        <title>Заказы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div class="row">
            <div class="col-3">
                <button id="btn-open-create-order-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="find-order-container col">                
                <form method="GET" class="form-inline">
                    <input class="form-control col-sm-5" type="text" id="order" placeholder="Введите фамилию клиента">
                    <button id="btn-find-order" class="btn btn-primary col-form-label">Найти</button>
                </form>
            </div>
            </div>
            <div class="form-group create-order-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="user" class="col-sm-2 col-form-label">Пользователь</label>
ORDERS;
            require_once '../Classes/User.php';
            $user = new User();
            $users = $user->ShowUsers();
            if ($users) {
                $usersLength = count($users);
                print "<select id=\"user\" class=\"form-control col-sm-4\">";
                for ($i=0; $i < $usersLength; $i++) { 
                    print "<option value=\"{$users[$i]->id}\">{$users[$i]->Login} ({$users[$i]->LName} {$users[$i]->FName} {$users[$i]->MName})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте пользователей в разделе <a href='users.php'><strong>Пользователи</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group row\">
                   <label for=\"car\" class=\"col-form-label col-sm-2\">Автомобиль</label>";
                    

            require_once '../Classes/Product.php';
            $car = new Product();
            $cars = $car->ShowProducts();
            if ($cars) {
                $carsLength = count($cars);
                print "<select id=\"car\" class=\"form-control col-sm-4\">";
                for ($i=0; $i < $carsLength; $i++) { 
                    print "<option value=\"{$cars[$i]->id}\">{$cars[$i]->Model} ({$cars[$i]->Type})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте автомобиль в разделе <a href='products.php'><strong>Автомобили</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group row\">
                    <label for=\"hours\" class=\"col-sm-2 col-form-label\">Количество часов</label>
                    <input class=\"form-control col-sm-4\" id=\"hours\">
                </div>
                <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            
            <div>
                <h2>Заказы</h2>";

                require_once '../Classes/Order.php';
                $order = new Order();
                $orders = $order->ShowOrders();
                if ($orders) {
                    $ordersLength = count($orders);
                    print "<table class=\"table table-bordered\">
                                        <thead>
                                            <th>id</th>
                                            <th>Логин</th>
                                            <th>ФИО</th>
                                            <th>Модель</th>
                                            <th>Кузов</th>
                                            <th>Время</th>
                                            <th>Цена</th>
                                            <th>Операции</th>
                                        </thead>
                                        <tbody>";
                    for ($i=0; $i < $ordersLength; $i++) { 
                        print "<tr>
                                    <td>{$orders[$i]->id}</td>
                                    <td>{$orders[$i]->Login}</td>
                                    <td>{$orders[$i]->User}</td>
                                    <td>{$orders[$i]->Model}</td>
                                    <td>{$orders[$i]->Type}</td>
                                    <td>{$orders[$i]->Hours}</td>
                                    <td>{$orders[$i]->Price}</td>
                                    <td><button class=\"btn btn-danger\">Удалить</button></td>
                                </tr>";
                    }
                    print "</tbody>";
                } else {
                    echo('Вы не создали ни одного заказа');
                }
            print "</div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/orders_scripts.js\"></script>      
    </body>
</html>";
    }
}
?>