<?php
class Order implements IOrder {
    public function CreateOrder($order)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $order, 'create')) {
            $createOrderQuery = $db->prepare("INSERT INTO orders VALUES (?, ?, ?)");
            $createOrderQuery->execute(array($order->id, $order->user, $order->car));    
        }
    }

    public function GetOrder($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectOrderQuery = $db->prepare("SELECT * FROM orders WHERE id = ?");
        $selectOrderQuery->execute(array($id));
        $order = $selectOrderQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($selectOrderQuery) == 1) {
            return $order;
        } else {
            echo('Возникла ошибка. Повторите позднее');
            return false;
        }
    }

    public function DeleteOrder($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $deleteOrderQuery = $db->prepare("DELETE FROM orders WHERE id = ?");
        $deleteOrderQuery->execute(array($id));
    }

    public function UpdateOrder($order)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        //дописать логику для варианта без фотки
        if ($this->CheckDublicates($db, $order, 'update')) {
            $updateOrderQuery = $db->prepare('UPDATE orders SET User_id = ? AND Product_id = ? WHERE id = ?');
            $updateOrderQuery->execute(array($order->user, $order->car, $order->id));
        }
        
    }
    
    protected function CheckDublicates($db, $order, $pointer)
    {
        if ($pointer === 'create') {
            $getOrderQuery = $db->prepare("SELECT * from orders WHERE User_id = ? AND Product_id = ?");
            $getOrderQuery->execute(array($order->user, $order->car));
            $currentOrder = $getOrderQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentOrder) == 0) {                
                return true;
            } else {
                echo ('Такой заказ уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getOrderQuery = $db->prepare("SELECT * from orders WHERE User_id = ? AND Product_id = ?");
            $getOrderQuery->execute(array($order->user, $order->car));
            $currentOrder = $getOrderQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentOrder) == 0 || count($currentOrder) == 1) {
                return true;
            } else {
                echo ('Такой заказ уже существует');
                return false;
            }            
        }
    }

    public function FindOrder($order)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        #ДОПИСАТЬ ЗАПРОС
        $findOrderQuery = $db->prepare("SELECT ord.id, u.Login, concat(u.LName, ' ', U.FName, ' ', u.MName) AS User, m.Title AS Model, cb.Type, pr.Price FROM orders AS ord INNER JOIN users AS u ON ord.User_id = u.id INNER JOIN products AS pr ON ord.Product_id = pr.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id WHERE u.LName = ?");
        $findOrderQuery->execute(array($order));        
        $currentOrder = $findOrderQuery->fetchAll(PDO::FETCH_OBJ);        
        if (count($currentOrder) != 0) {
            return $currentOrder;
        } else {
            return false;
        }        
    }

    public function ShowOrders()
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        #закончить заказ до конца
        $selectOrdersQuery = $db->prepare("SELECT ord.id, u.Login, concat(u.LName, ' ', U.FName, ' ', u.MName) AS User, m.Title AS Model, cb.Type, pr.Price FROM orders AS ord INNER JOIN users AS u ON ord.User_id = u.id INNER JOIN products AS pr ON ord.Product_id = pr.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id");
        $selectOrdersQuery->execute();
        $orders = $selectOrdersQuery->fetchAll(PDO::FETCH_OBJ);
        $ordersLength = count($orders);
        if ($ordersLength !=0) {
            return $orders;
        } else {            
            return false; 
        }
        
    }

    public function SetData($inputData, $order)
    {
        $order->id = uniqid();
        $order->user = $inputData->user;
        $order->car = $inputData->car;        
        return $order;    
    }

    public function CheckData($order)
    {
        try {
            if (($order->user ?? '') && ($order->car ?? '')) {
                return true;
            } else {
                throw new Exception("Wrong Data Error", 1);
            }
            
        } catch (Exception $error) {    
            if ($error->getMessage() === 'Wrong Data Error') {
                if (!($order->user ?? '')) {
                    echo("Вы не выбрали пользователя, на которого нужно арендовать автомобиль!\n");
                }
                if (!($order->car ?? '')) {
                    echo("Вы не выбрали автомобиль, которую нужно дать в аренду!\n");
                }
            }
        }

        
    }
}

interface IOrder {
    function CreateOrder($order);
    
    function GetOrder($id);

    function DeleteOrder($id);

    function UpdateOrder($order);

    function ShowOrders();

    function SetData($inputData, $order);

    function CheckData($inputData);

    function FindOrder($model);
}

?>