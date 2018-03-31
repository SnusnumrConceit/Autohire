<?php
class Order implements IOrder {
    public function CreateOrder($order, $db)
    {
        $db = DbConnect();
        if ($this->CheckDublicates($db, $order)) {
            $createOrderQuery = $db->prepare("INSERT INTO orders VALUES (?, ?, ?, ?)");
            $createOrderQuery->execute(array($order->id, $order->user, $order->product_id, $order->hours));    
        }
    }

    public function GetOrder($id)
    {
        require_once '../DbConnect.php';
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

        
    protected function CheckDublicates($db, $order)
    {
        $getOrderQuery = $db->prepare("SELECT * from orders WHERE User_id = ? AND Product_id = ?");
        $getOrderQuery->execute(array($order->user, $order->product_id));
        $currentOrder = $getOrderQuery->fetchAll();
        if (count($currentOrder) == 0) {                
            $getOrderQuery = $db->prepare("SELECT * from orders WHERE User_id = ?");
            $getOrderQuery->execute(array($order->user));
            $currentOrder = $getOrderQuery->fetchAll();
            if(count($currentOrder) >= 1) {
                echo ('Вы не можете арендовать более одного автомобиля!');
                return false;
            } else {
                return true;
            }
        } else {
            echo ('Такой заказ уже существует!');
            return false;
        }            
        
    }

    public function FindOrder($order)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
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
        $selectOrdersQuery = $db->prepare("SELECT ord.id, u.Login, concat(u.LName, ' ', U.FName, ' ', u.MName) AS User, m.Title AS Model, cb.Type, pr.Price, ord.Hours FROM orders AS ord INNER JOIN users AS u ON ord.User_id = u.id INNER JOIN products AS pr ON ord.Product_id = pr.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id");
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
        $order->product_id = $inputData->product_id;  
        $order->hours = $inputData->hours;     
        return $order;    
    }

    public function CheckData($order)
    {
        try {
            if (($order->user ?? '') && ($order->product_id ?? '') && ($order->hours ?? '')) {
                if (is_numeric($order->hours)) {
                    if (strlen($order->hours) <= 2) {
                        return true;
                    } else {
                        throw new Exception("Length Data Error", 1);
                    }
                } else {
                    throw new Exception("Wrong Data Error", 1);
                }
                
            } else {
                throw new Exception("Empty Data Error", 1);
            }
            
        } catch (Exception $error) {    
            if ($error->getMessage() === 'Empty Data Error') {
                if (!($order->user ?? '')) {
                    echo("Вы не выбрали пользователя, на которого нужно арендовать автомобиль!\n");
                }
                if (!($order->product_id ?? '')) {
                    echo("Вы не выбрали автомобиль, которую нужно дать в аренду!\n");
                }
                if (!($order->hours ?? '')) {
                    echo("Вы не указали количество часов!\n");
                }
            }

            if ($error->getMessage() === 'Length Data Error') {
                echo("Количество часов не может превышать 72х часов!");
            }

            if ($error->getMessage() === 'Wrong Data Error') {
                echo('Поле количество часов должно состоять из цифр!');
            }
        }

        
    }
}

interface IOrder {
    function CreateOrder($order, $db);
    
    function GetOrder($id);

    function DeleteOrder($id);

    function ShowOrders();

    function SetData($inputData, $order);

    function CheckData($inputData);

    function FindOrder($order);
}

?>