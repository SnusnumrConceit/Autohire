<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['option'] ?? '') {
            require_once '../../Classes/Option.php';
            $option = new Option();
            $id = $_GET['option'];
            $option = $option->GetOption($id);
            if ($option ?? '') {                
                print <<<OPTION
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Изменение модели {$option[0]->Title}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <a class="btn btn-default" href="../products.php">Назад</a>
            <h2>Модель {$option[0]->Title}</h2>
            <form>
                <div class="from-group">
                    <label for="title">Название</label>
                    <input class="form-control" type="text" id="title" value="{$option[0]->Title}">
                </div>
                <button class="btn btn-success" id="btnSubmitEdit" type="button">Изменить</button>
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../../Scripts/edit_options_scripts.js"></script>      
    </body>
</html>
OPTION;
            } else{
                echo('Данная запись не была найдена');
            }
        } /*else {
            echo('Вы совершили некорректное действие'); //надо бы возвращать 502 ошибку            
        }*/
    } if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['edit_option'] ?? '') {
            function CheckData($option) {
                try {                    
                    $optionLength = strlen($option->title);
                    if (($option->id ?? '') && ($option->title ?? '')) {
                        if ($optionLength >= 3 && $optionLength <= 25) {
                            if (trim($option->title) === $option->title && htmlentities($option->title) === $option->title ) {
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
                        echo('Вы не ввели название опции автомобиля!');
                    }
                    if ($error->getMessage() === 'Length Data Error') {
                        echo('Название опции автомобиля должно состоять от 3 до 25 символов!');
                    }
                    if ($error->getMessage() === 'Wrong Data Error') {
                        echo('Название опции автомобиля должно состоять из латинских и кириллистических букв!');
                    }
                    return false;
                }
            }                      
            $edit_option = json_decode($_POST['edit_option']);
            require_once '../../Classes/option.php';
            if (CheckData($edit_option)) {
                $option = new Option();
                $option->UpdateOption($edit_option);    
            }            
        } else {
            echo("Вы не ввели название опции автомобиля!");
        }
    }


?>

