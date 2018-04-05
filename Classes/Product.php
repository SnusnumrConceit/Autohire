<?php
class Product implements IProduct {
    protected $id;
    protected $brand;
    protected $model;
    protected $price;
    protected $photo;
    protected $body;
    protected $options;

    public function CreateProduct($product)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $product, 'create')) {
            $createProductQuery = $db->prepare("CALL spCreateProduct (?, ?, ?, ?, ?, ?)");
            $createProductQuery->execute(array($product->id, $product->brand, $product->model, $product->price, $product->photo, $product->body));
            $this->UpdateOptions($db, $product);
        }
    }

    public function GetProduct($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectProductQuery = $db->prepare("SELECT * FROM vproducts WHERE id = ?");
        $selectProductQuery->execute(array($id));
        $product = $selectProductQuery->fetchAll(PDO::FETCH_OBJ);
        if ($product) {
            $selectOptionsQuery = $db->prepare("CALL spGetProductOptions(?)");
            $productsLength = count($product);
            if ($productsLength == 1) {
                for ($i=0; $i < $productsLength; $i++) { 
                    $selectOptionsQuery->execute(array($product[$i]->id));
                    $options = $selectOptionsQuery->fetchAll(PDO::FETCH_OBJ);
                    if ($options) {
                        $product[$i]->Options = $options;
                    }
                }
                return $product;
            }    
        } else {            
            return false; 
        }
    }

    public function DeleteProduct($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteProductQuery = $db->prepare("CALL spDeleteProduct(?)");
        $deleteProductQuery->execute(array($id));
    }

    public function UpdateProduct($product)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        //дописать логику для варианта без фотки
        if ($this->CheckDublicates($db, $product, 'update')) {
            if ($product->photo ?? '') {
                $updateProductQuery = $db->prepare('CALL spUpdateProduct (?, ?, ?, ?, ?, ?)');
                $updateProductQuery->execute(array($product->brand, $product->model, $product->price, $product->photo, $product->body, $product->id));
                $this->UpdateOptions($db, $product);
            } else {
                $updateProductQuery = $db->prepare('CALL spUpdateProductMinPhoto(?, ?, ?, ?, ?)');
                $updateProductQuery->execute(array($product->brand, $product->model, $product->price, $product->body, $product->id));
                $this->UpdateOptions($db, $product);
            }
        }
        
    }
    protected function UpdateOptions($db, $product){
        if ($product->options ?? '') {
                $clearCharacteristics = $db->prepare("CALL spDeleteCharacteristic(?)");
                $clearCharacteristics->execute(array($product->id));
                $options = count($product->options);
                $createCharacteritics = $db->prepare("CALL spCreateCharacteristic(?,?)");
                for ($i=0; $i < $options; $i++) { 
                    $createCharacteritics->execute(array($product->id, $product->options[$i]));
                }
            }
    }
    protected function CheckDublicates($db, $product, $pointer)
    {
        if ($pointer === 'create') {
            $getProductQuery = $db->prepare("CALL spCheckDublicateProduct(?,?)");
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
            $getProductQuery = $db->prepare("CALL spCheckDublicateProduct(?,?)");
            $getProductQuery->execute(array($product->model, $product->body));
            $currentProduct = $getProductQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentProduct) == 0 || count($currentProduct) == 1) {
                return true;
            } else {
                echo ('Такой автомобиль уже существует');
                return false;
            }            
        }
    }

    public function FindProduct($product)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findProductQuery = $db->prepare('SELECT * FROM vproducts WHERE Model = ?');
        $findProductQuery->execute(array($product));        
        $currentProduct = $findProductQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentProduct) != 0) {
            return $currentProduct;
        } else {
            return false;
        }        
    }

    public function ShowProducts()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectProductsQuery = $db->prepare("SELECT * FROM vproducts");
        $selectProductsQuery->execute();
        $products = $selectProductsQuery->fetchAll(PDO::FETCH_OBJ);
        $selectOptionsQuery = $db->prepare("SELECT opt.Title FROM characteristics AS chr INNER JOIN options AS opt ON opt.id = chr.Option_id WHERE chr.Product_id = ?");
        $productsLength = count($products);
        if ($productsLength !=0) {
            for ($i=0; $i < $productsLength; $i++) { 
                $selectOptionsQuery->execute(array($products[$i]->id));
                $options = $selectOptionsQuery->fetchAll(PDO::FETCH_OBJ);
                if ($options) {
                    $products[$i]->Options = $options;
                }
            }
            return $products;
        } else {            
            return false; 
        }
        
    }

    public function SetData($product, $photo)
    {
        $this->id = uniqid();
        $this->brand = $product->brand;
        $this->model = $product->model;
        $this->price = $product->price;
        $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        $this->body = $product->body;
        $this->options = $product->options;
        return $this;    
    }

    public function CheckData($product, $photo)
    {
        try {
            if (($photo ?? '') && ($product->brand ?? '') &&($product->model ?? '') &&($product->body ?? '') &&($product->price ?? '')) {
                if (is_uploaded_file($photo['tmp_name'])) {
                    if ($photo['size'] < 2*1024*1024) {
                        $fileExtension = substr($photo['name'],-3,3);
                        $arrExtensions = ['jpg', 'png', 'JPG', '.PNG'];
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
                        throw new Exception('Image Size Error', 1);   
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

            if ($error->getMessage() === 'Image Size Error') {
                echo("Размер файла не должен превышать 2 МБайт!");
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

    function SetData($product, $photo);

    function CheckData($inputData, $photo);

    function FindProduct($model);
}

?>