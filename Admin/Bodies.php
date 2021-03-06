<?php
session_start();
    if ($_SESSION ?? '') {
        if ($_SESSION['name'] === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['car_body'] ?? '') {
            $inputData = json_decode($_POST['car_body']);            
            require_once '../Classes/CarBody.php';
            $carBody = new CarBody();
            if ($carBody->CheckData($inputData)) {
                $carBody = $carBody->SetData($inputData);
                $carBody->CreateBody($carBody);    
            }            
        } 
        elseif ($_POST['id'] ?? '') {
            $id = $_POST['id'];            
            require_once '../Classes/CarBody.php';
            $carBody = new CarBody();
            $carBody->DeleteBody($id);
        }
        
        else {
            echo('Ничего не пришло');
        }        
    }
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['body'] ?? '') {
            $inputData = $_GET['body'];
            print <<<CARBODIES
<!DOCTYPE html>
<html>
    <head>
        <title>Кузовы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
<div class="row">
                <div class="col-3">
                    <button id="btn-open-create-body-container" class="btn btn-success">Добавить</button>
                    <a class="btn btn-default" href="admin.php">На главную</a>
                </div>
                <div class="find-body-container col">                
                    <form method="GET" class="form-inline">
                        <input class="form-control col-sm-5" type="text" id="body" placeholder="Введите название кузова" value="{$inputData}">
                        <button id="btn-find-body" class="btn btn-primary col-form-label">Найти</button>
                    </form>
                </div>
            </div>
            <div class="form-group create-body-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="title" class="col-ofrm-label col-sm-2">Кузов</label>
                        <select id="title" class="form-control col-sm-4">    
                            <option value="купе">Купе</option>
                            <option value="хэтчбэк">Хэтчбек</option>
                            <option value="седан">Седан</option>
                        <select>
                    </div>
                    <div class="form-group row">
                        <label for="oil" class="col-form-label col-sm-2">Топливо</label>
                        <select id="oil" class="form-control col-sm-4">    
                            <option value="АИ-92">АИ-92</option>
                            <option value="АИ-95">АИ-95</option>
                            <option value="ДТ">Дизель</option>                            
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="transmission" class="col-form-label col-sm-2">Коробка передач</label>
                        <select id="transmission" class="form-control col-sm-4">    
                            <option value="МКПП">Механическая</option>
                            <option value="АКПП">Автоматическая</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="control" class="col-label-form col-sm-2">Привод</label>
                        <select id="control" class="form-control col-sm-4">
                            <option value="передний">Передний</option>
                            <option value="задний">Задний</option>
                        </select>
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-success">Отправить</button>    
                </form>
            </div>
            <h2>Кузовы</h2>
CARBODIES;
            require_once '../Classes/CarBody.php';
                        $bodies = new CarBody();
                        $bodies = $bodies->FindBody($inputData);
                        if ($bodies) {
                            $bodiesLength = count($bodies);
                            print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Кузов</th>
                                        <th>Топливо</th>
                                        <th>Коробка передач</th>
                                        <th>Привод</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                            for ($i=0; $i < $bodiesLength; $i++) { 
                                print "<tr>
                                            <td>{$bodies[$i]->id}</td>
                                            <td>{$bodies[$i]->Type}</td>
                                            <td>{$bodies[$i]->Oil}</td>
                                            <td>{$bodies[$i]->Transmission}</td>
                                            <td>{$bodies[$i]->Control}</td>
                                            <td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                        </tr>";
                            }
                            print "</div>
                                </div>
                            </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/car_bodies_scripts.js\"></script>      
    </body>
</html>";
                        } else {
                            echo("<div>По запросу <i>{$inputData}</i> ничего не найдено</div>
                            </div>
                        </div>
                    </div>  
                    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
                    <script src=\"../Scripts/car_bodies_scripts.js\"></script>      
                </body>
            </html>");
                        }

        }
    #####_____ОСНОВНАЯ VIEW________########
    else {
print <<<CARBODIES
<!DOCTYPE html>
<html>
    <head>
        <title>Кузовы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <button id="btn-open-create-body-container" class="btn btn-success">Добавить</button>
                    <a class="btn btn-default" href="admin.php">На главную</a>
                </div>
                <div class="find-body-container col">                
                    <form method="GET" class="form-inline">
                        <input class="form-control col-sm-5" type="text" id="body" placeholder="Введите название кузова" value="">
                        <button id="btn-find-body" class="btn btn-primary col-form-label">Найти</button>
                    </form>
                </div>
            </div>
            <div class="form-group create-body-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="title" class="col-ofrm-label col-sm-2">Кузов</label>
                        <select id="title" class="form-control col-sm-4">    
                            <option value="купе">Купе</option>
                            <option value="хэтчбэк">Хэтчбек</option>
                            <option value="седан">Седан</option>
                        <select>
                    </div>
                    <div class="form-group row">
                        <label for="oil" class="col-form-label col-sm-2">Топливо</label>
                        <select id="oil" class="form-control col-sm-4">    
                            <option value="АИ-92">АИ-92</option>
                            <option value="АИ-95">АИ-95</option>
                            <option value="ДТ">Дизель</option>                            
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="transmission" class="col-form-label col-sm-2">Коробка передач</label>
                        <select id="transmission" class="form-control col-sm-4">    
                            <option value="МКПП">Механическая</option>
                            <option value="АКПП">Автоматическая</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="control" class="col-label-form col-sm-2">Привод</label>
                        <select id="control" class="form-control col-sm-4">
                            <option value="передний">Передний</option>
                            <option value="задний">Задний</option>
                        </select>
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-success">Отправить</button>    
                </form>
            </div>

            <h2>Кузовы</h2>
CARBODIES;
                        require_once '../Classes/CarBody.php';
                        $carBodies = new CarBody();
                        $result = $carBodies->ShowBodies();
                        if($result){
                            print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Кузов</th>
                                        <th>Топливо</th>
                                        <th>Коробка передач</th>
                                        <th>Привод</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                            for ($i=0; $i < count($result); $i++) { 
                                print "<tr>
                                            <td>{$result[$i]->id}</td>
                                            <td>{$result[$i]->Type}</td>
                                            <td>{$result[$i]->Oil}</td>
                                            <td>{$result[$i]->Transmission}</td>
                                            <td>{$result[$i]->Control}</td>
                                            <td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                        </tr>";
                            }
                        } else {
                            echo('Вы не создали ни одного кузова');
                        }
print          "</div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/car_bodies_scripts.js\"></script>      
    </body>
</html>";
        }
    }
} else {
        header('location: ../enter.php');
    }
} else {
    header('location: ../enter.php');
}
?>