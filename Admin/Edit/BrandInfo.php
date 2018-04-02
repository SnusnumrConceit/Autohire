<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['brand'] ?? '') {
            require_once '../../Classes/Brand.php';
            $brand = new Brand();
            $id = $_GET['brand'];
            $brand = $brand->GetBrand($id);
            if ($brand ?? '') {                
                print <<<BRAND
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Изменение модели {$brand[0]->Title}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <a class="btn btn-default" href="../products.php">Назад</a>
            <h2>Модель {$brand[0]->Title}</h2>
            <form>
                <div class="from-group">
                    <label for="title">Название</label>
                    <input class="form-control" type="text" id="title" value="{$brand[0]->Title}">
                </div>
                <button class="btn btn-success" id="btnSubmitEdit" type="button">Изменить</button>
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../../Scripts/edit_brands_scripts.js"></script>      
    </body>
</html>
BRAND;
            } else{
                echo('Данная запись не была найдена');
            }
        } /*else {
            echo('Вы совершили некорректное действие'); //надо бы возвращать 502 ошибку            
        }*/
    } if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['edit_brand'] ?? '') {
             function CheckData($brand) {
                try {                    
                    $brandLength = strlen($brand->title);
                    if (($brand->id ?? '') && ($brand->title ?? '')) {
                        if ($brandLength >= 3 && $brandLength <= 25) {
                            if (trim($brand->title) === $brand->title && htmlentities($brand->title) === $brand->title ) {
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
            $edit_brand = json_decode($_POST['edit_brand']);            
            require_once '../../Classes/Brand.php';
            if (CheckData($edit_brand)) {
                $brand = new Brand();
                $brand->UpdateBrand($edit_brand);    
            }
           
        } else {
            echo('Вы не ввели название марки автомобиля!');
        }
    }
?>

