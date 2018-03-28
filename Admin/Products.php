<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        if (($_POST['product'] ?? '') && ($_FILES['photo'] ?? '')) {
            $inputData = json_decode($_POST['product']); 
            $photo = $_FILES['photo'];
            require_once '../Classes/Product.php';
            $product = new Product();
            if($product->CheckData($inputData, $photo)) {
                $product = $product->SetData($inputData, $product, $photo);                
                $product->CreateProduct($product);
            }            
        } 
        elseif ($_POST['id'] ?? '') {
            $id = $_POST['id'];            
            require_once '../Classes/Product.php';
            $product = new Product();
            $product->DeleteProduct($id);
        }        
        else {
            echo('Ничего не пришло');
        }        
    }
    #####_____ПОИСКОВАЯ VIEW________########
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['product'] ?? '') {
            $inputData = $_GET['product'];
print <<<PRODUCTS
<!DOCTYPE html>
<html>
    <head>
        <title>Автомобильный ряд</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-product-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-product-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo">Картинка</label>
                        <input type="file" class="form-control" id="photo">
                    </div>
                    <div class="form-group">
                        <label for="brand">Марка</label>
                        <select class="form-control" id="brand">
PRODUCTS;
                    require_once '../Classes/Brand.php';
                    $brand = new Brand();
                    $brands = $brand->ShowBrands();
                    $brandsLength = count($brands);                    
                    for ($i=0; $i < $brandsLength; $i++) { 
                        print "<option value=\"{$brands[$i]->id}\">{$brands[$i]->Title}</option>";
                    }
                    print "</select>
                    </div>
                    <div class=\"form-group\">
                        <label for=\"model\">Модель</label>
                        <select class=\"form-control\" id=\"model\">";
                    require_once '../Classes/Model.php';
                    $model = new Model();
                    $models = $model->ShowModels();
                    $modelsLength = count($models);
                    for ($i=0; $i < $modelsLength; $i++) { 
                        print "<option value=\"{$models[$i]->id}\">{$models[$i]->Title}</option>";
                    }
                    print "</select>
                    </div>
                    <div class=\"form-group\">
                        <label for=\"price\">Цена</label>
                        <input type=\"text\" class=\"form-control\" id=\"price\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"car-body\">Кузов</label>
                        <select class=\"form-control\" id=\"car-body\">";
                    require_once '../Classes/CarBody.php';
                    $carBody = new CarBody();
                    $carBodies = $carBody->ShowBodies();
                    $carBodiesLength = count($carBodies);
                    for ($i=0; $i < $carBodiesLength; $i++) { 
                        print "<option value=\"{$carBodies[$i]->id}\">{$carBodies[$i]->Type}</option>";
                    }
                    print   "</select>
                    </div>";
                     print "<div class=\"form-group\">";
                    require_once '../Classes/Option.php';
                    $option = new Option();
                    $options = $option->ShowOptions();
                    $optionsLength = count($options);
                    if ($options) {
                        for ($i=0; $i < $optionsLength; $i++) { 
                            print "<label>{$options[$i]->Title}</label>
                                    <input type=\"checkbox\" value=\"{$options[$i]->id}\">";
                        }
                    } else {
                        echo("<div>Для добавления дополнительных опций в автомобильный ряд сначала добавьте данные опции в разделе <a href='options.php'><strong>Опции</strong></a></div>");
                    }
                    print "</div>
                    <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <div class=\"find-product-container\">                
                <form method=\"GET\">
                    <input class=\"form-control\" type=\"text\" id=\"product\" placeholder=\"Введите модель автомобиля\" value=\"{$inputData}\">
                    <button id=\"btn-find-product\" class=\"btn btn-primary\">Найти</button>
                </form>
            </div>
            <div>
                <h2>Автомобильный ряд</h2>";

            require_once '../Classes/Product.php';
            $product = new Product();
            $findlessProducts = $product->FindProduct($inputData);            
            if ($findlessProducts) {                
                print "<table class=\"table table-bordered\">
                                        <thead>
                                            <th>id</th>
                                            <th>Фото</th>
                                            <th>Марка</th>
                                            <th>Модель</th>
                                            <th>Цена</th>
                                            <th>Кузов</th>
                                            <th>Коробка передач</th>
                                            <th>Топливо</th>
                                            <th>Привод</th>
                                            <th>Операции</th>
                                    </thead>
                                        <tbody>";
                    for ($i=0; $i < count($findlessProducts); $i++) { 
                        print "<tr>
                                    <td>{$findlessProducts[$i]->id}</td>
                                    <td><img width=\"180\" height=\"120\" src=\"data:image/jpg;base64,{$findlessProducts[$i]->Photo}\"></td>
                                    <td>{$findlessProducts[$i]->Brand}</td>                                            
                                    <td>{$findlessProducts[$i]->Model}</td>
                                    <td>{$findlessProducts[$i]->Price}</td>
                                    <td>{$findlessProducts[$i]->Type}</td>
                                    <td>{$findlessProducts[$i]->Transmission}</td>
                                    <td>{$findlessProducts[$i]->Oil}</td>
                                    <td>{$findlessProducts[$i]->Control}</td>
                                    <td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                </tr>";
                    }
                    print "</tbody>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/products_scripts.js\"></script>      
    </body>
</html>";
            } else {
                echo("<div>По запросу <i>{$inputData}</i> не найдено ни одного товара</div>
                <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/products_scripts.js\"></script>      
    </body>
</html>");
            }
        }         
    
    else {



#####_____ОСНОВНАЯ VIEW________########
print <<<PRODUCTS
<!DOCTYPE html>
<html>
    <head>
        <title>Автомобильный ряд</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
         <div class="container">
            <div>
                <button id="btn-open-create-product-container" class="btn btn-success">Добавить</button>
                <a class="btn btn-default" href="admin.php">На главную</a>
            </div>
            <div class="form-group create-product-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo">Картинка</label>
                        <input type="file" class="form-control" id="photo">
                    </div>
                    <div class="form-group">
                        <label for="brand">Марка</label>
PRODUCTS;
                    require_once '../Classes/Brand.php';
                    $brand = new Brand();
                    $brands = $brand->ShowBrands();
                    $brandsLength = count($brands);
                    if ($brands) {
                        print "<select class=\"form-control\" id=\"brand\">";
                        for ($i=0; $i < $brandsLength; $i++) { 
                            print "<option value=\"{$brands[$i]->id}\">{$brands[$i]->Title}</option>";
                        }
                        print "</select>";
                    } else {
                        echo("<div>Для добавления автомобиля в автомобильный ряд сначала добавьте марку автомобиля в разделе <a href='brands.php'><strong>Марки</strong></a></div>");
                    }
                    print "</div>
                    <div class=\"form-group\">
                        <label for=\"model\">Модель</label>";
                    require_once '../Classes/Model.php';
                    $model = new Model();
                    $models = $model->ShowModels();
                    $modelsLength = count($models);
                    if ($models) {
                        print "<select class=\"form-control\" id=\"model\">";
                        for ($i=0; $i < $modelsLength; $i++) { 
                            print "<option value=\"{$models[$i]->id}\">{$models[$i]->Title}</option>";
                        }
                        print "</select>";
                    } else {
                        echo("<div>Для добавления автомобиля в автомобильный ряд сначала добавьте модель автомобиля в разделе <a href='models.php'><strong>Модели</strong></a></div>");
                    }
                    
                    print "</div>
                    <div class=\"form-group\">
                        <label for=\"price\">Цена</label>
                        <input type=\"text\" class=\"form-control\" id=\"price\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"car-body\">Кузов</label>";
                    require_once '../Classes/CarBody.php';
                    $carBody = new CarBody();
                    $carBodies = $carBody->ShowBodies();
                    $carBodiesLength = count($carBodies);
                    if ($carBodies) {
                        print "<select class=\"form-control\" id=\"car-body\">";
                        for ($i=0; $i < $carBodiesLength; $i++) { 
                            print "<option value=\"{$carBodies[$i]->id}\">{$carBodies[$i]->Type}</option>";
                        }
                        print "</select>";
                    } else {
                        echo("<div>Для добавления автомобиля в автомобильный ряд сначала добавьте кузов автомобиля в разделе <a href='bodies.php'><strong>Кузовы</strong></a></div>");
                    }
                    
                    print "</div>
                            <div class=\"form-group\">";
                    require_once '../Classes/Option.php';
                    $option = new Option();
                    $options = $option->ShowOptions();
                    $optionsLength = count($options);
                    if ($options) {
                        for ($i=0; $i < $optionsLength; $i++) { 
                            print "<label>{$options[$i]->Title}</label>
                                    <input type=\"checkbox\" value=\"{$options[$i]->id}\">";
                        }
                    } else {
                        echo("<div>Для добавления дополнительных опций в автомобильный ряд сначала добавьте данные опции в разделе <a href='options.php'><strong>Опции</strong></a></div>");
                    }
                    
                    print "</div>
                    <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <div class=\"find-product-container\">                
                <form method=\"GET\">
                    <input class=\"form-control\" type=\"text\" id=\"product\" placeholder=\"Введите модель автомобиля\">
                    <button id=\"btn-find-product\" class=\"btn btn-primary\">Найти</button>
                </form>
            </div>
            <div>
                <h2>Автомобильный ряд</h2>";

                        require_once '../Classes/Product.php';
                        $product = new Product();
                        $result = $product->ShowProducts();
                        if($result){
                            print "<table class=\"table table-bordered\">
                                    <thead>
                                        <th>id</th>
                                        <th>Фото</th>
                                        <th>Марка</th>
                                        <th>Модель</th>
                                        <th>Цена</th>
                                        <th>Кузов</th>
                                        <th>Коробка передач</th>
                                        <th>Топливо</th>
                                        <th>Привод</th>
                                        <th>Опции</th>
                                        <th>Операции</th>
                                    </thead>
                                    <tbody>";
                            require_once '../wideimage/lib/wideimage.php';
                            for ($i=0; $i < count($result); $i++) { 
                                $img = base64_decode($result[$i]->Photo);
                                $img = WideImage::load($img);
                                $img = $img->resize(180, 120);
                                $img = base64_encode($img);
                                print "<tr>
                                            <td>{$result[$i]->id}</td>
                                            <td><img src=\"data:image/jpg;base64,{$img}\"></td>
                                            <td>{$result[$i]->Brand}</td>                                            
                                            <td>{$result[$i]->Model}</td>
                                            <td>{$result[$i]->Price}</td>
                                            <td>{$result[$i]->Type}</td>
                                            <td>{$result[$i]->Transmission}</td>
                                            <td>{$result[$i]->Oil}</td>
                                            <td>{$result[$i]->Control}</td>";
                                if ($result[$i]->Options ?? '') {
                                   print "<td><ul>";
                                   for ($j=0; $j < count($result[$i]->Options); $j++) { 
                                        print "<li>{$result[$i]->Options[$j]->Title}</li>";
                                    }
                                    print "</ul></td>";
                                } else {
                                    print "<td></td>";
                                }
                                    print "<td><button class=\"btn btn-warning\">Изменить</button><button class=\"btn btn-danger\">Удалить</button></td>
                                        </tr>";
                            }
                        } else {
                            echo('Вы не создали ни одного товара');                            
                        }
print               "</tbody>
                </div>
            </div>
        </div>  
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../Scripts/products_scripts.js\"></script>      
    </body>
</html>";
    }
}
?>