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
    /*elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        require_once '../Classes/Model.php';
                $id = file_get_contents('php://input');
                $model = new Model();        
                $model->DeleteModel($id);
        
    } */ 

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
            <div class="form-group">            
                <form method="POST">
                    <label for="title">Название модели</label>
                    <input type="text" id="title" value="" class="form-control">
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>
                </form>
                <div>
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
?>