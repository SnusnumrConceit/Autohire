<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST ?? '') {
            if ($_POST['model'] ?? '') {
                require_once '../Classes/Model.php';
                $title = $_POST['model'];
                $model = new Model();    
                if ($model->CheckData($title)) {
                    $model = $model->SetData($title, $model);
                    $model->CreateModel($model);   
                }
            }
            elseif ($_POST['id'] ?? '') {
                require_once '../Classes/Model.php';
                $id = $_POST['id'];
                $model = new Model();        
                $model->DeleteModel($id);                
            } else {
                echo('Вы не ввели название модели автомобиля!');
            }
            
        }       
    
    } 
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['model'] ?? '') {
            $inputData = $_GET['model'];
            print <<<OPTION
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Модели</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div>
                <button id="btn-open-create-model-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-model-container">
                <form method="POST">
                    <label for="title">Название опции</label>
                    <input type="text" id="title" class="form-control">
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>  
                </form>
            </div>
            <div class="find-model-container">                
                <form method="GET">
                    <input class="form-control" type="text" id="model" value="{$inputData}" placeholder="Введите название модели">
                    <button id="btn-find-model" class="btn btn-primary">Найти</button>
                </form>
            </div>
            <div>
                <h2>Модели</h2>
OPTION;
                    require_once '../Classes/Model.php';
                    $model = new Model();
                    $models = $model->FindModel($inputData);
                    $findlessLength = count($models);
                    if ($models) {
                        print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Название</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                        for ($i=0; $i < $findlessLength; $i++) { 
                        print "<tr>
                                    <td>{$models[$i]->id}</td>
                                    <td>{$models[$i]->Title}</td>
                                    <td><button class=\"btn btn-warning\">Изменение</button><button class=\"btn btn-danger\">Удаление</button></td>                                        
                                </tr>";
                        }      
                        print "</tbody>
                            </table>
            </div>
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/models_scripts.js\"></script>      
    </body>
</html>";
                } else {
                    echo("<div>По запросу <i>{$inputData}</i> не найдено ни одной автомобильной модели</div>
            </div>
         <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/models_scripts.js\"></script>      
    </body>
</html>");
    }
} 
    #####_____ОСНОВНАЯ VIEW________########
else {
print <<<MODELS
<!DOCTYPE html>
<html>
    <head>
        <title>Модели</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div>
                <button id="btn-open-create-model-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-model-container">            
                <form method="POST">
                    <label for="title">Название модели</label>
                    <input type="text" id="title" value="" class="form-control">
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>
                </form>
            </div>
            <div class="find-model-container">                
                <form method="GET">
                    <input class="form-control" type="text" id="model" placeholder="Введите название модели">
                    <button id="btn-find-model" class="btn btn-primary">Найти</button>
                </form>
            </div>
            <div>
            <h2>Модели</h2>
MODELS;
                        require_once '../Classes/Model.php';                
                        $models = new Model();
                        $result = $models->ShowModels();                        
                        print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Название</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                        for ($i=0; $i < count($result); $i++) { 
                            print "<tr>
                                        <td>{$result[$i]->id}</td>
                                        <td>{$result[$i]->Title}</td>
                                        <td><button class=\"btn btn-warning\">Изменение</button><button class=\"btn btn-danger\">Удаление</button></td>                                        
                                    </tr>";
                        }      
                        print "</tbody>
                                </table>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/models_scripts.js\"></script>      
    </body>
</html>";
    }
}
?>