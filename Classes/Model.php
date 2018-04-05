<?php
class Model implements IModel {

    protected $id;
    protected $title;

    public function ShowModels()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectModelsQuery = $db->prepare('SELECT * FROM vmodels');
        $selectModelsQuery->execute();
        $models = $selectModelsQuery->fetchAll(PDO::FETCH_OBJ);
        if ($models != 0) {
            return $models;
        } else {
            echo('Вы не создали ни одной модели автомобиля');
            return false;
        }
        
    }
    

    public function CreateModel($model)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $model, 'create')) {
            $insertModelQuery = $db->prepare("CALL spCreateModel(?, ?)");
            $insertModelQuery->execute(array($model->id, $model->title));    
        }       
        
    }

    public function UpdateModel($model) {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if($this->CheckDublicates($db, $model, 'update')) {
            $updateModelQuery = $db->prepare("CALL spUpdateModel(?,?)");
            $updateModelQuery->execute(array($model->title, $model->id));
        }
    }

    protected function CheckDublicates($db, $model, $pointer)
    {
        if ($pointer === 'create') {
            $getModelQuery = $db->prepare("SELECT * from vmodels WHERE Title = ?");
            $getModelQuery->execute(array($model->title));
            $currentModel = $getModelQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentModel) == 0) {                
                return true;
            } else {
                echo ('Такая модель автомобиля уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getModelQuery = $db->prepare("SELECT * from vmodels WHERE Title = ?");
            $getModelQuery->execute(array($model->title));
            $currentModel = $getModelQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentModel) == 0) {
                return true;
            } else {
                echo ('Такая модель автомобиля уже существует');
                return false;
            }            
        }
    }
    public function DeleteModel($id) {
        require_once 'DbConnect.php';
        $db = DbConnect();                
        $deleteModelQuery = $db->prepare("CALL spDeleteModel(?)");
        $deleteModelQuery->execute(array($id));
    }

    public function FindModel($model)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findModelQuery = $db->prepare('SELECT * FROM vmodels WHERE Title = ?');
        $findModelQuery->execute(array($model));        
        $currentProduct = $findModelQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentProduct) != 0) {
            return $currentProduct;
        } else {
            return false;
        }        
    }

    public function GetModel($id)
    {        
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getModelQuery = $db->prepare('SELECT * FROM vmodels WHERE id = ?');
        $getModelQuery->execute(array($id));
        $model = $getModelQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($model) == 1) {
            return $model;
        } else {
            return false;
        }
        
    }

    public function CheckData($inputData)
    {
        try {
            $title = $inputData;
            $titleLength = mb_strlen($title);
            if ($titleLength != 0) {
                if ($titleLength > 3 && $titleLength <= 25) {
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
                echo("Вы не ввели название модели автомобиля!\n");
                return false;
            }
            if ($message === 'Length Data Error') {
                echo("Название модели автомобиля должно быть от 3 до 25 символов!\n");
                return false;
            }
            if ($message === 'Wrong Data Error') {
                echo("Название модели автомобиля должно состоять из латинских или кириллистических букв!\n");
                return false;
            }
        }

    }
    
    public function SetData($title)
    {
        $this->id = uniqid();
        $this->title = $title;
        return $this;
    }
}


interface IModel { 
    
    function ShowModels();

    function GetModel($id);
    
    function CreateModel($title);    

    function UpdateModel($id);    

    function DeleteModel($id);

    function CheckData($inputData);
    
    function SetData($title);
    
    function FindModel($model);
}

?>