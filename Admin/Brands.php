<?php
session_start();
    if ($_SESSION ?? '') {
        if ($_SESSION['name'] === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['brand'] ?? '') {
            include_once '../Classes/Brand.php';
            $title = $_POST['brand'];
            $brand = new Brand();
            if ($brand->CheckData($title)) {
                $brand = $brand->SetData($title);
                $brand->CreateBrand($brand);
            }                             
        } elseif ($_POST['id'] ?? '') {
            include_once '../Classes/Brand.php';
            $id = $_POST['id'];
            $brand = new Brand();
            $brand->DeleteBrand($id);
        }
        else {
            echo('Вы не ввели название марки автомобиля!');
        }
                
  
    
    }
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['brand'] ?? '') {
            $inputData = $_GET['brand'];
            print <<<OPTION
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Марки</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <button id="btn-open-create-brand-container" class="btn btn-success">Добавить</button>
                    <a class="btn btn-default" href="admin.php">На главную</a>
                </div>
                <div class="find-brand-container col">                
                    <form method="GET" class="form-inline">
                        <input class="form-control col-sm-5" type="text" id="brand" placeholder="Введите название марки" value="{$inputData}">
                        <button id="btn-find-brand" class="btn btn-primary col-form-label">Найти</button>
                    </form>
                </div>
            </div>
            <div class="form-group create-brand-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">Название марки</label>
                        <input type="text" id="title" value="" class="form-control col-sm-4">
                    </div>
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>
                </form>
            </div>
                <h2>Марки</h2>
OPTION;
                    
                    require_once '../Classes/Brand.php';
                    $brand = new Brand();
                    $brands = $brand->FindBrand($inputData);
                    if ($brands) {
                        $findlessLength = count($brands);
                        print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Название</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                        for ($i=0; $i < $findlessLength; $i++) { 
                        print "<tr>
                                    <td>{$brands[$i]->id}</td>
                                    <td>{$brands[$i]->Title}</td>
                                    <td><button class=\"btn btn-warning\">Изменение</button><button class=\"btn btn-danger\">Удаление</button></td>                                        
                                </tr>";
                        }      
                        print "</tbody>
                            </table>
            </div>
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/brands_scripts.js\"></script>      
    </body>
</html>";
                } else {
                    echo("<div>По запросу <i>{$inputData}</i> не найдено ни одной автомобильной марки</div>
            </div>
         <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/brands_scripts.js\"></script>      
    </body>
</html>");
} 
    #####_____ОСНОВНАЯ VIEW________########
}else {
print <<<BRANDS
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Марки</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <button id="btn-open-create-brand-container" class="btn btn-success">Добавить</button>
                    <a class="btn btn-default" href="admin.php">На главную</a>
                </div>
                <div class="find-brand-container col">                
                    <form method="GET" class="form-inline">
                        <input class="form-control col-sm-5" type="text" id="brand" placeholder="Введите название марки">
                        <button id="btn-find-brand" class="btn btn-primary col-form-label">Найти</button>
                    </form>
                </div>
            </div>
            <div class="form-group create-brand-container">
                <form method="POST">
                    <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">Название марки</label>
                        <input type="text" id="title" value="" class="form-control col-sm-4">
                    </div>
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>
                </form>
            </div>
            <h2>Марки</h2> 
BRANDS;
                        include_once '../Classes/Brand.php';                
                        $brands = new Brand();
                        $result = $brands->ShowBrands(); 
                        if ($result) {
                            
                                               
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
                            echo("Вы не добавили ни одной автомобильной марки");
                        }
    print    "</div>
        </div> 
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/brands_scripts.js\"></script>       
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