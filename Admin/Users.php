<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['user'] ?? '') {
            $inputData = json_decode($_POST['user']);                        
            require_once '../Classes/User.php';
            $user = new User();
            if($user->CheckData($inputData)) {
                $user = $user->SetData($inputData, $user);
                $user->CreateUser($user);
            }            
        } 
        elseif ($_POST['id'] ?? '') {
            $id = $_POST['id'];            
            require_once '../Classes/User.php';
            $user = new User();
            $user->DeleteUser($id);
        }        
        else {
            echo('Ничего не пришло');
        }        
    }
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['user'] ?? '') {
            $inputData = $_GET['user'];
                print <<<USER
<!DOCTYPE html>
<html>
    <head>
        <title>Пользователи</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-user-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-user-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Номер телефона</label>
                        <input type="text" class="form-control" id="phone-number">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Фамилия</label>
                        <input type="text" class="form-control" id="last-name">
                    </div>
                    <div class="form-group">
                        <label for="first-name">Имя</label>
                        <input type="text" class="form-control" id="first-name">
                    </div>
                    <div class="form-group">
                        <label for="middle-name">Отчество</label>
                        <input type="text" class="form-control" id="middle-name">
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-success">Отправить</button>    
                </form>
            </div>
            <div class="find-user-container">                
                <form method="GET">
                    <input class="form-control" type="text" id="user" value="{$inputData}" placeholder="Введите фамилию пользователя">
                    <button id="btn-find-user" class="btn btn-primary">Найти</button>
                </form>
            </div>
            <div>
                    <h2>Пользователи</h2>
USER;
                    require_once '../Classes/User.php';
                    $user = new User();
                    $findlessUsers = $user->FindUser($inputData);            
                    if ($findlessUsers) {       
                        print "<table class=\"table table-bordered\">
                                        <thead>
                                            <th>id</th>
                                            <th>Логин</th>
                                            <th>Фамилия</th>
                                            <th>Имя</th>
                                            <th>Отчество</th>
                                            <th>Номер телефона</th>
                                            <th>Операции</th>
                                        </thead>
                                        <tbody>";

                    for ($i=0; $i < count($findlessUsers); $i++) { 
                        print "<tr>
                                <td>{$findlessUsers[$i]->id}</td>
                                <td>{$findlessUsers[$i]->Login}</td>
                                <td>{$findlessUsers[$i]->LName}</td>
                                <td>{$findlessUsers[$i]->FName}</td>
                                <td>{$findlessUsers[$i]->MName}</td>
                                <td>{$findlessUsers[$i]->Phone}</td>
                                <td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                </tr>";
                    }
                    print "</tbody>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/users_scripts.js\"></script>      
    </body>
</html>";
            } else {
                print "<div>По запросу <i>{$inputData}</i> не найдено ни одного пользователя</div>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/users_scripts.js\"></script>      
    </body>
</html>";
            }
        }         
    #####_____ОСНОВНАЯ VIEW________########
    else {
print <<<USERS
<!DOCTYPE html>
<html>
    <head>
        <title>Пользователи</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-user-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-user-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Номер телефона</label>
                        <input type="text" class="form-control" id="phone-number">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Фамилия</label>
                        <input type="text" class="form-control" id="last-name">
                    </div>
                    <div class="form-group">
                        <label for="first-name">Имя</label>
                        <input type="text" class="form-control" id="first-name">
                    </div>
                    <div class="form-group">
                        <label for="middle-name">Отчество</label>
                        <input type="text" class="form-control" id="middle-name">
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-success">Отправить</button>    
                </form>
            </div>
            <div class="find-user-container">                
                <form method="GET">
                    <input class="form-control" type="text" id="user" placeholder="Введите фамилию пользователя">
                    <button id="btn-find-user" class="btn btn-primary">Найти</button>
                </form>
            </div>
            <button id="btn-csv-user" class="btn btn-default">Выгрузить список пользователей в CSV</button>
            <div>
                <h2>Пользователи</h2>
USERS;
                        require_once '../Classes/User.php';
                        $user = new User();
                        $result = $user->ShowUsers();
                        if($result){
                            print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Логин</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Отчество</th>
                                        <th>Номер телефона</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                            for ($i=0; $i < count($result); $i++) { 
                                print "<tr>
                                            <td>{$result[$i]->id}</td>
                                            <td>{$result[$i]->Login}</td>                                            
                                            <td>{$result[$i]->LName}</td>
                                            <td>{$result[$i]->FName}</td>
                                            <td>{$result[$i]->MName}</td>
                                            <td>{$result[$i]->Phone}</td>
                                            <td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                        </tr>";
                            }
                        } else {
                            echo('Вы не создали ни одного пользователя');                            
                        }
print               "</tbody>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js\"></script>
        <script src=\"../Scripts/users_scripts.js\"></script>      
    </body>
</html>";
    }
}
?>