<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['brand'] ?? '') {
            include_once '../Classes/Brand.php';
            $title = $_POST['brand'];
            $brand = new Brand();
            if ($brand->CheckData($title)) {
                $brand = $brand->SetData($title, $brand);
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
else {
print <<<BRANDS
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Марки</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
        <button id="btn-open-create-brand-container" class="btn btn-success">Добавить</button>
            <div class="form-group create-brand-container">
                <form method="POST">
                <div class="form-group">
                    <label for="title">Название марки</label>
                    <input type="text" id="title" value="" class="form-control">
                    <button type="button" class="btn btn-success" id="btnSubmit">Отправить</button>
                </div>
                </form>
            </div>
            <div class="find-brand-container">                
                <form method="GET">
                    <input class="form-control" type="text" id="brand" value="">
                    <button id="btn-find-brand" class="btn btn-primary">Найти</button>
                </form>
            </div>   
            <div> 
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
?>