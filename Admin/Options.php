<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['option'] ?? '') {
            require_once '../Classes/Option.php';
            $inputData = $_POST['option'];
            $option = new Option();  
            if($option->CheckData($inputData)) {
                $option = $option->SetData($inputData, $option);
                $option->CreateOption($option);
            }            
        } elseif ($_POST['id'] ?? '') {
            require_once '../Classes/Option.php';
            $id = $_POST['id'];
            $option = new Option();                    
            $option->DeleteOption($id);
        } else {
            echo('Вы не ввели название опции автомобиля!');
        }
    }
else {
print <<<OPTIONS
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Оцпии</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="form-group">
                
            
                <form method="POST">
                    <label for="title">Название опции</label>
                    <input type="text" id="title" class="form-control">
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>  
                </form>
                <div>
OPTIONS;
                        require_once '../Classes/Option.php';                
                        $options = new Option();
                        $result = $options->ShowOptions();  
                        if($result ?? '') {

                                           
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
                                </table>";
                        } else {
                            echo("Вы не создали ни одной опции");
                        }                
        print "</div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/options_scripts.js\"></script>            
    </body>
</html>";
}
?>