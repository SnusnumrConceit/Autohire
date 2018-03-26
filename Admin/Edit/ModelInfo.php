<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['model'] ?? '') {
            require_once '../../Classes/Model.php';
            $model = new Model();
            $id = $_GET['model'];
            $model = $model->GetModel($id);
            if ($model ?? '') {                
                print <<<MODEL
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Изменение модели {$model[0]->Title}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h2>Модель {$model[0]->Title}</h2>
            <form>
                <div class="from-group">
                    <label for="title">Название</label>
                    <input class="form-control" type="text" id="title" value="{$model[0]->Title}">
                </div>
                <button class="btn btn-success" id="btnSubmitEdit" type="button">Изменить</button>
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../../Scripts/edit_models_scripts.js"></script>      
    </body>
</html>
MODEL;
            } else{
                echo('Данная запись не была найдена');
            }
        } /*else {
            echo('Вы совершили некорректное действие'); //надо бы возвращать 502 ошибку            
        }*/
    } if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['edit_model'] ?? '') {
             function CheckData($model) {
                try {                    
                    $modelLength = strlen($model->title);
                    if (($model->id ?? '') && ($model->title ?? '')) {
                        if ($modelLength >= 3 && $modelLength <= 25) {
                            if (trim($model->title) === $model->title && htmlentities($model->title) === $model->title ) {
                                return true;
                            } else {
                                throw new Exception("Wrong Data Error", 1);
                            }
                        } else {
                            throw new Exception("Length Data Error", 1);
                        }
                    } else {
                        throw new Exception("Emtpy Data Error", 1);                        
                    }
                } catch (Exception $error) {
                    if ($error->getMessage() === 'Empty Data Error') {
                        echo('Вы не ввели название модели автомобиля!');
                    }
                    if ($error->getMessage() === 'Length Data Error') {
                        echo('Название модели автомобиля должно состоять от 3 до 25 символов!');
                    }
                    if ($error->getMessage() === 'Wrong Data Error') {
                        echo('Название модели автомобиля должно состоять из латинских и кириллистических букв!');
                    }
                    return false;
                }
            }                      
            $edit_model = json_decode($_POST['edit_model']);            
            require_once '../../Classes/Model.php';
            if (CheckData($edit_model)) {
                $model = new Model();
                $model->UpdateModel($edit_model);    
            }
           
        } else {
            echo('Вы не ввели название модели автомобиля!');
        }
    }
?>

