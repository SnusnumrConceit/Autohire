<?php
session_start();
    if ($_SESSION ?? '') {
        if ($_SESSION['name'] === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['body'] ?? '') {
            require_once '../../Classes/CarBody.php';
            $body = new CarBody();
            $id = $_GET['body'];
            $body = $body->GetBody($id);
            if ($body ?? '') {  
echo ("
<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Изменение кузова {$body[0]->Type}</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet'' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css'>
    </head>
    <body>
        <div class='container'>
            <a class='btn btn-default' href='../bodies.php'>Назад</a>
            <h2>Кузов {$body[0]->Type} {$body[0]->Transmission}</h2>
            <form method='POST'>
                    <div class='form-group'>
                        <label for='title'>Кузов</label>
                        <select id='title' class='form-control'>");

                        if ($body[0]->Type == 'купе') {
                            echo("<option value='купе' selected>Купе</option>");
                        } else {
                            echo("<option value='купе'>Купе</option>");
                        }
                        if ($body[0]->Type == 'хэтчбэк') {
                            echo("<option value='хэтчбэк' selected>Хэтчбек</option>");
                        } else {
                            echo("<option value='хэтчбэк'>Хэтчбек</option>");
                        }
                        if ($body[0]->Type == 'седан') {
                            echo("<option value='седан' selected>Седан</option>");
                        } else {
                            echo("<option value='седан'>Седан</option>");
                        }
                        echo("</select>
                    </div>
                    <div class='form-group'>
                        <label for='oil'>Топливо</label>
                        <select id='oil' class='form-control'>");
                            if ($body[0]->Oil == 'АИ-92') {
                                echo("<option value='АИ-92' selected>АИ-92</option>");
                            } else {
                                echo("<option value='АИ-92'>АИ-92</option>");
                            }
                            if ($body[0]->Oil == 'АИ-95') {
                                echo("<option value='АИ-95' selected>АИ-95</option>");
                            } else {
                                echo("<option value='АИ-95'>АИ-95</option>");
                            }
                            if ($body[0]->Oil == 'ДТ') {
                                echo("<option value='ДТ' selected>Дизель</option>");
                            } else {
                                echo("<option value='ДТ'>Дизель</option>");
                            }
                        echo("</select>
                    </div>
                    <div class='form-group'>
                        <label for='transmission'>Коробка передач</label>
                        <select id='transmission' class='form-control'>");
                        if ($body[0]->Transmission == 'МКПП') {
                            echo("<option value='МКПП' selected>Механическая</option>");
                        } else {
                            echo("<option value='МКПП'>Механическая</option>");
                        }
                        if ($body[0]->Transmission == 'АКПП') {
                            echo("<option value='АКПП' selected>Автоматическая</option>");
                        } else {
                            echo("<option value='АКПП'>Автоматическая</option>");
                        }
                        echo ("</select>
                    </div>
                    <div>
                        <label for='control'>Привод</label>
                        <select id='control' class='form-control'>
                            <option value='передний'>Передний</option>
                            <option value='задний'>Задний</option>                            
                        </select>
                    </div>
                    <button type='button' id='btnSubmitEdit' class='btn btn-success'>Отправить</button>    
                </form>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../../Scripts/edit_bodies_scripts.js'></script>      
    </body>
</html>");

            } else{
                echo('Данная запись не была найдена');
            }
        } 
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
} else {
        header('location: ../../enter.php');
    }
} else {
    header('location: ../../enter.php');
}
?>