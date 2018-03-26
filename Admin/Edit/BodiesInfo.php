<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['body'] ?? '') {
            require_once '../../Classes/CarBody.php';
            $body = new CarBody();
            $id = $_GET['body'];
            $body = $body->GetBody($id);
            if ($body ?? '') {                
                print <<<CAR_BODY
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Изменение кузова {$body[0]->Type}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h2>Кузов {$body[0]->Type} {$body[0]->Transmission}</h2>
            <form method="POST">
                    <div class="form-group">
                        <label for="title">Кузов</label>
                        <select id="title" class="form-control">    
                            <option value="купе">Купе</option>
                            <option value="хэтчбэк">Хэтчбек</option>
                            <option value="седан">Седан</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="oil">Топливо</label>
                        <select id="oil" class="form-control">    
                            <option value="АИ-92">АИ-92</option>
                            <option value="АИ-95">АИ-95</option>
                            <option value="ДТ">Дизель</option>                  
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transmission">Коробка передач</label>
                        <select id="transmission" class="form-control">    
                            <option value="МКПП">Механическая</option>
                            <option value="АКПП">Автоматическая</option>                            
                        </select>
                    </div>
                    <div>
                        <label for="control">Привод</label>
                        <select id="control" class="form-control">
                            <option value="передний">Передний</option>
                            <option value="задний">Задний</option>                            
                        </select>
                    </div>
                    <button type="button" id="btnSubmitEdit" class="btn btn-success">Отправить</button>    
                </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../../Scripts/edit_bodies_scripts.js"></script>      
    </body>
</html>
CAR_BODY;
            } else{
                echo('Данная запись не была найдена');
            }
        } /*else {
            echo('Вы совершили некорректное действие'); //надо бы возвращать 502 ошибку            
        }*/
    } if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['edit_body'] ?? '') {              
            $edit_body = json_decode($_POST['edit_body']);            
            require_once '../../Classes/CarBody.php';
            $body = new CarBody();
            if ($body->CheckData($edit_body)) {
                $body->UpdateBody($edit_body);    
            }           
        }
    }
?>

