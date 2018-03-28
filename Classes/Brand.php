<?php
class Brand implements IBrand{
    public function CreateBrand($brand)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $brand, 'create')) {
            $insertBrandQuery = $db->prepare("INSERT INTO brands VALUES (?,?)");
            $insertBrandQuery->execute(array($brand->id, $brand->title));    
        }
    }

    public function DeleteBrand($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $deleteBrandQuery = $db->prepare("DELETE FROM brands WHERE id = ?");
        $deleteBrandQuery->execute(array($id));
    }

    public function UpdateBrand($brand)
    {
        require_once '../../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $brand, 'update')) {
            $updateBrandQuery = $db->prepare("UPDATE brands SET Title = ? WHERE id = ?");
            $updateBrandQuery->execute(array($brand->title, $brand->id));
        }
    }

    public function FindBrand($brand)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $findBrandQuery = $db->prepare('SELECT * FROM brands WHERE Title = ?');
        $findBrandQuery->execute(array($brand));        
        $currentProduct = $findBrandQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentProduct) != 0) {
            return $currentProduct;
        } else {
            return false;
        }        
    }

    protected function CheckDublicates($db, $brand, $pointer)
    {
        if ($pointer === 'create') {
            $getBrandQuery = $db->prepare("SELECT * from brands WHERE Title = ?");
            $getBrandQuery->execute(array($brand->title));
            $currentBrand = $getBrandQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentBrand) == 0) {                
                return true;
            } else {
                echo ('Такая модель автомобиля уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getBrandQuery = $db->prepare("SELECT * from brands WHERE Title = ?");
            $getBrandQuery->execute(array($brand->title));
            $currentBrand = $getBrandQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentBrand) == 0) {
                return true;
            } else {
                echo ('Такая модель автомобиля уже существует');
                return false;
            }            
        }
    }

    public function SetData($inputData, $brand)
    {
        $brand->id = uniqid();
        $brand->title = $inputData;
        return $brand;
    }

    public function GetBrand($id)
    {
        require_once '../../DbConnect.php';
        $db = DbConnect();
        $getBrandQuery = $db->prepare("SELECT * FROM brands WHERE id = ?");
        $getBrandQuery->execute(array($id));
        $selectedBrand = $getBrandQuery->fetchAll(PDO::FETCH_OBJ);
        if(count($selectedBrand) != 0) {
            return $selectedBrand;
        } else {
            echo("Данная автомобильная марка не была найдена");
        }
    }

    public function ShowBrands()
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectBrandQuery = $db->prepare("SELECT * FROM brands");
        $selectBrandQuery->execute();
        $brands = $selectBrandQuery->fetchAll(PDO::FETCH_OBJ);
        if(count($brands) != 0) {
            return $brands;
        } else {
            return false;
        }
    }

    public function CheckData($inputData)
    {
        try {
            $title = $inputData;
            $titleLength = strlen($title);
            if ($titleLength != 0) {
                if ($titleLength >= 3 && $titleLength <= 25) {
                    if(htmlspecialchars($title) == $title) {
                        if(trim($title) == $title) {
                            return true;
                        } else {
                            throw new Exception('Wrong Data Error');
                        }
                    } else {
                        throw new Exception('Wrong Data Error');
                    }
                } else {
                    throw new Exception('Length Data Error');
                }                
            } else {
                throw new Exception('Empty Data Error');
            }
            
        }
        catch(Exception $error) {
            $message = $error->getMessage();
            if ($message === 'Empty Data Error') {
                echo('Вы не ввели название марки автомобиля!');
                return false;
            }
            if ($message === 'Length Data Error') {
                echo('Название марки автомобиля должно быть от 3 до 25 символов!');
                return false;
            }
            if ($message === 'Wrong Data Error') {
                echo('Название марки автомобиля должно состоять из латинских или кириллистических букв!');
                return false;
            }
        }
    }
}

interface IBrand {
    function CreateBrand($brand);

    function DeleteBrand($id);
    
    function GetBrand($id);
  
    function UpdateBrand($brand);

    function SetData($inputData, $brand);

    function CheckData($inputData);

    function ShowBrands();

    function FindBrand($brand);
    
}

?>