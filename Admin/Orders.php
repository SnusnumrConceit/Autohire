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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-order-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-order-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="user">Пользователь</label>
ORDERS;
            require_once '../Classes/User.php';
            $user = new User();
            $users = $user->ShowUsers();
            $usersLength = count($users);
            if ($users) {
                print "<select id=\"user\" class=\"form-control\">";
                for ($i=0; $i < $usersLength; $i++) { 
                    print "<option value=\"{$users[$i]->id}\">{$users[$i]->Login} ({$users[$i]->LName} {$users[$i]->FName} {$users[$i]->MName})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте пользователей в разделе <a href='users.php'><strong>Пользователи</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group\">
                   <label for=\"car\">Автомобиль</label>";
                    

            require_once '../Classes/Product.php';
            $car = new Product();
            $cars = $car->ShowProducts();
            if ($cars) {
                print "<select id=\"car\" class=\"form-control\">";
                for ($i=0; $i < $carsLength; $i++) { 
                    print "<option value=\"{$cars[$i]->id}\">{$cars[$i]->Model} ({$cars[$i]->Type})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте автомобиль в разделе <a href='products.php'><strong>Автомобили</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group\">
                    <label for=\"hours\">Количество часов</label>
                    <input class=\"form-control\" id=\"hours\">
                </div>
                <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <div class=\"find-order-container\">                
                <form method=\"GET\">
                    <input class=\"form-control\" type=\"text\" id=\"order\" placeholder=\"Введите фамилию клиента\" value=\"{$inputData}\">
                    <button id=\"btn-find-order\" class=\"btn btn-primary\">Найти</button>
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-order-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-order-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="user">Пользователь</label>
ORDERS;
            require_once '../Classes/User.php';
            $user = new User();
            $users = $user->ShowUsers();
            $usersLength = count($users);
            if ($users) {
                print "<select id=\"user\" class=\"form-control\">";
                for ($i=0; $i < $usersLength; $i++) { 
                    print "<option value=\"{$users[$i]->id}\">{$users[$i]->Login} ({$users[$i]->LName} {$users[$i]->FName} {$users[$i]->MName})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте пользователей в разделе <a href='users.php'><strong>Пользователи</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group\">
                   <label for=\"car\">Автомобиль</label>";
                    

            require_once '../Classes/Product.php';
            $car = new Product();
            $cars = $car->ShowProducts();
            $carsLength = count($cars);
            if ($cars) {
                print "<select id=\"car\" class=\"form-control\">";
                for ($i=0; $i < $carsLength; $i++) { 
                    print "<option value=\"{$cars[$i]->id}\">{$cars[$i]->Model} ({$cars[$i]->Type})</option>";
                }
                print "</select>";
            } else {
                print "<div>Для оформления заказа сначала добавьте автомобиль в разделе <a href='products.php'><strong>Автомобили</strong></a></div>";
            }
            print "</div>
                <div class=\"form-group\">
                    <label for=\"hours\">Количество часов</label>
                    <input class=\"form-control\" id=\"hours\">
                </div>
                <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <div class=\"find-order-container\">                
                <form method=\"GET\">
                    <input class=\"form-control\" type=\"text\" id=\"order\" placeholder=\"Введите фамилию клиента\">
                    <button id=\"btn-find-order\" class=\"btn btn-primary\">Найти</button>
                </form>
            </div>
            <div>
                <h2>Заказы</h2>";

                require_once '../Classes/Order.php';
                $order = new Order();
                $orders = $order->ShowOrders();
                $ordersLength = count($orders);
                if ($orders) {
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