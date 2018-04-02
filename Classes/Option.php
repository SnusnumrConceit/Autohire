<?php
class Option implements IOption{
    public function CreateOption($option)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $option, 'create')) {
            $insertOptionQuery = $db->prepare("INSERT INTO options VALUES (?,?)");
            $insertOptionQuery->execute(array($option->id, $option->title));    
        }        
    }

    public function DeleteOption($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteOptionQuery = $db->prepare("DELETE FROM options WHERE id =?");
        $deleteOptionQuery->execute(array($id));
    }

    public function UpdateOption($option)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $option, 'update')) {
            $updateOptionQuery = $db->prepare("UPDATE options SET Title = ? WHERE id = ?");
            $updateOptionQuery->execute(array($option->title, $option->id));
        }     
        
    }

    public function FindOption($option)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findOptionQuery = $db->prepare('SELECT * FROM options WHERE Title = ?');
        $findOptionQuery->execute(array($option));        
        $currentProduct = $findOptionQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentProduct) != 0) {
            return $currentProduct;
        } else {
            return false;
        }        
    }

    public function SetData($inputData, $option)
    {
        $option->id = uniqid();
        $option->title = $inputData;
        return $option;
    }

    public function GetOption($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getOptionQuery = $db->prepare("SELECT * FROM options WHERE id = ?");
        $getOptionQuery->execute(array($id));
        $selectedOption = $getOptionQuery->fetchAll(PDO::FETCH_OBJ);
        if(count($selectedOption) != 0) {
            return $selectedOption;
        } else {
            echo("Данная опция не была найдена");
        }
    }

    public function ShowOptions()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectOptionQuery = $db->prepare("SELECT * FROM options");
        $selectOptionQuery->execute();
        $options = $selectOptionQuery->fetchAll(PDO::FETCH_OBJ);
        if(count($options) != 0) {
            return $options;
        } else {
            return false;
        }
    }
    protected function CheckDublicates($db, $option, $pointer)
    {
        if ($pointer === 'create') {
            $getOptionQuery = $db->prepare("SELECT * from options WHERE Title = ?");
            $getOptionQuery->execute(array($option->title));            
            $currentOption = $getOptionQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentOption) == 0) {                     
                return true;
            } else {
                echo ('Такая опция автомобиля уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getOptionQuery = $db->prepare("SELECT * from options WHERE Title = ?");
            $getOptionQuery->execute(array($option->title));
            $currentOption = $getOptionQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentOption) == 0) {
                return true;
            } else {
                echo ('Такая опция автомобиля уже существует');
                return false;
            }            
        }
    }

    public function CheckData($inputData)
    {
        try {
            $title = $inputData;
            $titleLength = strlen($title);
            if ($titleLength != 0) {
                if ($titleLength > 2 && $titleLength <= 25) {
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
                echo('Вы не ввели название опции автомобиля!');
                return false;
            }
            if ($message === 'Length Data Error') {
                echo('Название опции автомобиля должно быть от 3 до 25 символов!');
                return false;
            }
            if ($message === 'Wrong Data Error') {
                echo('Название опции автомобиля должно состоять из латинских или кириллистических букв!');
                return false;
            }
        }
    }
}

interface IOption {
    function CreateOption($option);

    function DeleteOption($id);
    
    function GetOption($id);
  
    function UpdateOption($option);

    function SetData($inputData, $option);

    function CheckData($inputData);

    function ShowOptions();

    function FindOption($option);
    
}

?>