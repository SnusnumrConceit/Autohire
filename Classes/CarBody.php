<?php
//класс, отвечающий за тип кузова, топливо, коробку передач и привод
//поскольку известен тип кузова, значит известны его стандартные механические характеристики
class CarBody implements ICarBody {
    private $id;
    private $type;
    private $oil;
    private $transmission;
    private $control;

public function CreateBody($carBody)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $carBody, 'create')) {
            $createBodyQuery = $db->prepare("INSERT INTO carbodies VALUES (?, ?, ?, ?, ?)");
            $createBodyQuery->execute(array($carBody->id, $carBody->type, $carBody->oil, $carBody->transmission, $carBody->control));
        } 
    }

    public function DeleteBody($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $deleteBodyQuery = $db->prepare("DELETE FROM carbodies WHERE id = ?");
        $deleteBodyQuery->execute(array($id));        
    }

    public function GetBody($id)
    {
        require_once '../../DbConnect.php';
        $db = DbConnect();
        $getBodyQuery = $db->prepare('SELECT * FROM carbodies WHERE id = ?');
        $getBodyQuery->execute(array($id));
        $selectedBodyQuery = $getBodyQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($selectedBodyQuery) == 1)   {
            return $selectedBodyQuery;
        } else {
            echo("Данный кузов не найден");
        }
        
    }

    public function UpdateBody($carBody)
    {
        require_once '../../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $carBody, 'update')) {
            $updateBodyQuery = $db->prepare("UPDATE carbodies SET Type = ?, Oil = ?, Transmission = ?, Control = ? WHERE id = ?");
            $updateBodyQuery->execute(array($carBody->type, $carBody->oil, $carBody->transmission, $carBody->control, $carBody->id));
        }
    }

    public function SetData($inputData, $carBody)
    {
        $carBody->id = uniqid();
        $carBody->type = $inputData->type;
        $carBody->oil = $inputData->oil;
        $carBody->transmission = $inputData->transmission;
        $carBody->control = $inputData->control;
        return $carBody;
    }

    public function CheckData($body)
    {
        $types = ['купе', 'хэтчбэк', 'седан'];
        $oils = ['АИ-92', 'АИ-95', 'ДТ'];
        $transmissions = ['МКПП', 'АКПП'];
        $controls = ['передний', 'задний'];

        try {
            if (($body->type ?? '') && ($body->oil ?? '') && ($body->transmission ?? '') && ($body->control ?? '')) {
                if (in_array($body->type, $types) && in_array($body->oil, $oils) && 
                    in_array($body->transmission, $transmissions) && in_array($body->control, $controls)) {
                    return true;
                } else {
                    throw new Exception("Wrong Data Error", 1);
                }
            } else {
                throw new Exception("Empty Data Error", 1);
            }
        } catch (Exception $error) {
            if ($error->getMessage() === 'Empty Data Error') {
                if (!($body->type ?? '')) {  
                    var_dump($body);
                    echo("Вы не выбрали тип кузова!\n");
                }

                if (!($body->oil ?? '')) {
                    echo("Вы не выбрали вид топлива!\n");
                }

                if (!($body->transmission ?? '')) {
                    echo("Вы не выбрали вид КПП!\n");
                }

                if (!($body->control ?? '')) {
                    echo("Вы не выбрали тип привода!\n");
                }
            }

            if ($error->getMessage() === 'Wrong Data Error') {
                if (!in_array($body->type, $types)) {
                    var_dump($body);
                    echo("Ошибка! Такого вида кузова не существует!\n");
                }

                if (!in_array($body->oil, $oils)) {
                    echo("Ошибка! Такого вида топлива не существует!\n");
                }

                if (!in_array($body->transmission, $transmissions)) {
                    echo("Ошибка! Такого вида коробки передач не существует!\n");
                }

                if (!in_array($body->control, $controls)) {
                    echo("Ошибка! Такого типа привода не существует!\n");
                }
            }
        }
    }

    public function ShowBodies()
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectBodiesQuery = $db->prepare("SELECT * FROM carbodies");
        $selectBodiesQuery->execute();
        $bodies = $selectBodiesQuery->fetchAll(PDO::FETCH_OBJ);
        $bodiesLength = count($bodies);
        if ($bodiesLength != 0) {
            return $bodies;
        } else {            
            return false;
        }
    }

     protected function CheckDublicates($db, $carBody, $pointer)
    {
        if ($pointer === 'create') {
            $getBodyQuery = $db->prepare("SELECT * from carbodies WHERE Type = ? AND Oil = ? AND Transmission = ? AND Control = ?");
            $getBodyQuery->execute(array($carBody->type, $carBody->oil, $carBody->transmission, $carBody->control));
            $currentModel = $getBodyQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentModel) == 0) {                
                return true;
            } else {
                echo ('Такая конфигурация автомобиля уже существует!');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getBodyQuery = $db->prepare("SELECT * from carbodies WHERE Type = ? AND Oil = ? AND Transmission = ? AND Control = ?");
            $getBodyQuery->execute(array($carBody->type, $carBody->oil, $carBody->transmission, $carBody->control));
            $currentModel = $getBodyQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentModel) == 0) {
                return true;
            } else {
                echo ('Такая конфигурация автомобиля уже существует!');
                return false;
            }            
        }
    }
}

interface ICarBody {
    function CreateBody($carBody);

    function DeleteBody($id);
    
    function GetBody($id);
  
    function UpdateBody($carBody);

    function SetData($inputData, $carBody);

    function CheckData($inputData);

    function ShowBodies();
}
    
?>