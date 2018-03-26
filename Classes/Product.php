<?php
class Product implements IProduct {
    public function CreateProduct($product)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $product, 'create')) {
            $createProductQuery = $db->prepare("INSERT INTO products VALUES (?, ?, ?, ?, ?, ?)");
            $createProductQuery->execute(array($product->id, $product->brand, $product->model, $product->price, $product->photo, $product->body));    
        }
    }

    public function GetProduct($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectProductQuery = $db->prepare("SELECT * FROM products WHERE id = ?");
        $selectProductQuery->execute(array($id));
        $product = $selectProductQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($selectProductQuery) == 1) {
            return $product;
        } else {
            echo('Возникла ошибка. Повторите позднее');
            return false;
        }
    }

    public function DeleteProduct($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $deleteProductQuery = $db->prepare("DELETE FROM products WHERE id = ?");
        $deleteProductQuery->execute(array($id));
    }

    public function UpdateProduct($product)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        //дописать логику для варианта без фотки
        if ($this->CheckDublicates($db, $product, 'update')) {
            $updateProductQuery = $db->prepare('UPDATE products SET Brand_id = ? AND Model_id = ? AND Price = ? AND Photo = ? AND CarBody_id = ? WHERE id = ?');
            $updateProductQuery->execute(array($product->brand, $product->model, $product->price, $product->photo, $product->body));
        }
        
    }
    
    protected function CheckDublicates($db, $product, $pointer)
    {
        if ($pointer === 'create') {
            $getProductQuery = $db->prepare("SELECT * from products WHERE Model_id = ? AND CarBody_id = ?");
            $getProductQuery->execute(array($product->model, $product->body));
            $currentProduct = $getProductQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentProduct) == 0) {                
                return true;
            } else {
                echo ('Такой автомобиль уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getProductQuery = $db->prepare("SELECT * from products WHERE Login = ?");
            $getProductQuery->execute(array($product->login));
            $currentProduct = $getProductQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentProduct) == 0 || count($currentProduct) == 1) {
                return true;
            } else {
                echo ('Такой автомобиль уже существует');
                return false;
            }            
        }
    }

    public function FindProduct($model)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $findProductQuery = $db->prepare('SELECT pr.id, b.Title AS Brand, m.Title AS Model, pr.Price, pr.Photo, cb.Type, cb.Oil, cb.Transmission, cb.Control  FROM products AS pr INNER JOIN brands AS b ON pr.Brand_id = b.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id WHERE m.Title = ?');
        $findProductQuery->execute(array($model));        
        $currentProduct = $findProductQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentProduct) != 0) {
            return $currentProduct;
        } else {
            return false;
        }        
    }

    public function ShowProducts()
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectProductsQuery = $db->prepare("SELECT pr.id, b.Title AS Brand, m.Title AS Model, pr.Price, pr.Photo, cb.Type, cb.Oil, cb.Transmission, cb.Control  FROM products AS pr INNER JOIN brands AS b ON pr.Brand_id = b.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id");
        $selectProductsQuery->execute();
        $products = $selectProductsQuery->fetchAll(PDO::FETCH_OBJ);
        $productsLength = count($products);
        if ($productsLength !=0) {
            return $products;
        } else {            
            return false; 
        }
        
    }

    public function SetData($inputData, $product, $photo)
    {
        $product->id = uniqid();
        $product->brand = $inputData->brand;
        $product->model = $inputData->model;
        $product->price = $inputData->price;
        $product->photo = base64_encode(file_get_contents($photo['tmp_name']));
        $product->body = $inputData->body;
        return $product;    
    }

    public function CheckData($inputData, $photo)
    {
        $product = $inputData;
        try {
            if (($photo ?? '') && ($product->brand ?? '') &&($product->model ?? '') &&($product->body ?? '') &&($product->price ?? '')) {
                if (is_uploaded_file($photo['tmp_name'])) {
                    $fileExtension = substr($photo['name'],-3,3);
                    $arrExtensions = ['jpg', 'png'];
                    if (in_array($fileExtension,$arrExtensions)) {
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
                        throw new Exception("Image Extension Error", 1);
                    }                    
                } else {
                    throw new Exception("Image Download Error", 1);
                    
                }
            } else {
                throw new Exception("Empty Data Error", 1);                
            }
            
        }
        catch (Exception $error) {
            if ($error->getMessage() === "Empty Data Error") {
                if(!($product->photo ?? '')) {
                    echo("Вы не добавили фотографию!\n");
                }

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

            if ($error->getMessage() === 'Image Download Error') {
                echo('Вы не загрузили картинку!');
                return false;
            }

            if ($error->getMessage() === 'Image Extension Error') {
                echo('Картинка должна быть в расширении .jpg или .png!');
                return false;
            }
            
        }
    }
}

interface IProduct {
    function CreateProduct($product);
    
    function GetProduct($id);

    function DeleteProduct($id);

    function UpdateProduct($product);

    function ShowProducts();

    function SetData($inputData, $product, $photo);

    function CheckData($inputData, $photo);

    function FindProduct($model);
}

?>