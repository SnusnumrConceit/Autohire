<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['product'] ?? '') {
            require_once '../../Classes/Product.php';
            $product = new Product();
            $id = $_GET['product'];
            $product = $product->GetProduct($id);
            if ($product ?? '') {                
                print <<<PRODUCT
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Изменение модели {$product[0]->Model}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <a class="btn btn-default" href="../products.php">Назад</a>
            <h2>Модель {$product[0]->Model}</h2>
            <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo">Картинка</label>
                        <input type="file" class="form-control-file" id="photo">
                    </div>
                    <div class="form-group">
                        <label for="brand">Марка</label>
                        <select class="form-control" id="brand">
PRODUCT;
                    require_once '../../Classes/Brand.php';
                    $brand = new Brand();
                    $brands = $brand->ShowBrands();
                    $brandsLength = count($brands);                    
                    for ($i=0; $i < $brandsLength; $i++) { 
                        if ($brands[$i]->Title == $product[0]->Brand) {
                            print "<option value=\"{$brands[$i]->id}\">{$brands[$i]->Title}</option>";
                        } else {
                            print "<option value=\"{$brands[$i]->id}\" selected>{$brands[$i]->Title}</option>";
                        }
                    }
                    print "</select>
                    </div>
                    <div class=\"form-group\">
                        <label for=\"model\">Модель</label>
                        <select class=\"form-control\" id=\"model\">";
                    require_once '../../Classes/Model.php';
                    $model = new Model();
                    $models = $model->ShowModels();
                    $modelsLength = count($models);
                    for ($i=0; $i < $modelsLength; $i++) { 
                        if ($models[$i]->Title == $product[0]->Model) {
                            print "<option value=\"{$models[$i]->id}\" selected>{$models[$i]->Title}</option>";
                        } else {
                            print "<option value=\"{$models[$i]->id}\">{$models[$i]->Title}</option>";
                        }
                        
                    }
                    print "</select>
                    </div>
                    <div class=\"form-group\">
                        <label for=\"price\">Цена</label>
                        <input type=\"text\" class=\"form-control\" id=\"price\" value=\"{$product[0]->Price}\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"car-body\">Кузов</label>
                        <select class=\"form-control\" id=\"car-body\">";
                    require_once '../../Classes/CarBody.php';
                    $carBody = new CarBody();
                    $carBodies = $carBody->ShowBodies();
                    $carBodiesLength = count($carBodies);
                    for ($i=0; $i < $carBodiesLength; $i++) { 
                        if ($carBodies[$i]->id == $product[$i]->Body) {
                            print "<option value=\"{$carBodies[$i]->id}\" selected>{$carBodies[$i]->Type}</option>";
                        }
                        else {
                            print "<option value=\"{$carBodies[$i]->id}\">{$carBodies[$i]->Type}</option>";
                        }
                        
                    }
                    print   "</select>
                    </div>";
                     print "<div class=\"form-group\">";
                    require_once '../../Classes/Option.php';
                    $option = new Option();
                    $options = $option->ShowOptions();
                    $optionsLength = count($options);
                    if ($options) {
                        $optionValidArr = [];
                        for ($i=0; $i < $optionsLength; $i++) { 
                            for ($j=0; $j < count($product[0]->Options); $j++) { 
                                if ($options[$i]->Title == $product[0]->Options[$j]->Title) {
                                    array_push($optionValidArr, $i);
                                }
                            }
                            if (in_array($i, $optionValidArr)) {
                                print "<div class=\"form-check form-check-inline\">
                                            <label class=\"form-check-label\">
                                                <input type=\"checkbox\" class=\"form-check-input\" value=\"{$options[$i]->id}\" checked>{$options[$i]->Title}
                                            </label>
                                        </div>";
                            } else {
                                print "<div class=\"form form-check-inline\">
                                        <label class=\"form-check-label\">
                                            <input type=\"checkbox\" class=\"form-check-input\" value=\"{$options[$i]->id}\">{$options[$i]->Title}
                                        </label>
                                        </div>";
                            }
                        }
                        
                    } else {
                        echo("<div>Для добавления дополнительных опций в автомобильный ряд сначала добавьте данные опции в разделе <a href='options.php'><strong>Опции</strong></a></div>");
                    }
                    print "</div>
                    <button type=\"button\" id=\"btnSubmit\" class=\"btn btn-success\">Отправить</button>    
                </form>
            </div>
            <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"../../Scripts/edit_product_scripts.js\"></script>      
    </body>
</html>";
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['product'] ?? '') {
            $inputData = json_decode($_POST['product']);
            $id = $inputData->id;
            require_once '../../Classes/Product.php';
            $product = new Product();
            //var_dump($inputData);
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                if ($product->CheckData($inputData, $photo)) {
                    $product = $product->SetData($inputData, $product, $photo);
                    $product->id = $id;
                    $product->UpdateProduct($product);                    
                }
            } else {
                function CheckDataWithoutPhoto($product)
                {
                    try {
            if (($product->brand ?? '') &&($product->model ?? '') &&($product->body ?? '') &&($product->price ?? '')) {
                    if (strlen($product->price) <= 4 && strlen($product->price) >= 3) {
                                if (is_numeric($product->price)) {
                                    if (trim($product->price) == $product->price) {
                                        if (htmlentities($product->price) == $product->price) {
                                            return true;
                                        } else {
                                            throw new Exception("Wrong Data Error", 1);
                                        }
                                    } else {
                                        throw new Exception("Wrong Data Error", 1);
                                    }    
                                } else {
                                    throw new Exception("Wrong Data Error", 1);
                                }
                                
                            } else {
                                throw new Exception("Data Length Error", 1);                   
                            }
            } else {
                throw new Exception("Empty Data Error", 1);                
            }
            
        }
        catch (Exception $error) {
            if ($error->getMessage() === "Empty Data Error") {

                if(!($product->brand ?? '')) {
                    echo("Пришли неверные данные о выбранной марке автомобиля!\n");
                }

                if(!($product->model ?? '')) {
                    echo("Пришли неверные данные о выбранной модели автомобиля!\n");
                }

                if(!($product->body ?? '')) {
                    echo("Пришли неверные данные о выбранном кузове!\n");
                }

                if(!($product->price ?? '')) {
                    echo("Вы не указали цену проката!\n");
                }
                return false;
            }

            if ($error->getMessage() === 'Data Length Error') {
                if (strlen($product->price) > 4 || strlen($product->price) < 3) {
                    echo("Цена проката не должна превышать 4 символов!\n");
                }
                return false;
            }
            
            if ($error->getMessage() === 'Wrong Data Error') {
                if (trim($product->price) != $product->price || htmlentities($product->price) != $product->price || !is_numeric($product_price)) {
                    echo("Неверный формат цены!");
                }
                return false;
            }

        }
    }
                if (CheckDataWithoutPhoto($inputData)) {
                    $inputData->id = $id;
                    $inputData->photo = null;
                    $product->UpdateProduct($inputData);
                }
            }
        } else {
            echo("Данные не пришли!");
        }
        
    }
?>