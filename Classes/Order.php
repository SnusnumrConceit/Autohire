<?php
class Order implements IOrder {
    protected $id;
    protected $user;
    protected $product_id;
    protected $hours;

    public function CreateOrder($order)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $order)) {
            $createOrderQuery = $db->prepare("CALL spCreateOrder (?, ?, ?, ?)");
            $createOrderQuery->execute(array($order->id, $order->user, $order->product_id, $order->hours));    
        }
    }

    public function GetOrder($id)
    {
        require_once 'DbConnect.php';
        $selectOrderQuery = $db->prepare("SELECT * FROM vorders WHERE id = ?");
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
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteOrderQuery = $db->prepare("CALL spDeleteOrder(?)");
        $deleteOrderQuery->execute(array($id));
    }

        
    protected function CheckDublicates($db, $order)
    {
        $getOrderQuery = $db->prepare("CALL spCheckDublicateOrder(?,?)");
        $getOrderQuery->execute(array($order->user, $order->product_id));
        $currentOrder = $getOrderQuery->fetchAll();
        if (count($currentOrder) == 0) {                
            $getOrderQuery = $db->prepare("CALL spCheckUserOrders (?)");
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
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findOrderQuery = $db->prepare("CALL spFindOrder(?)");
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
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectOrdersQuery = $db->prepare("SELECT * FROM vorders");
        $selectOrdersQuery->execute();
        $orders = $selectOrdersQuery->fetchAll(PDO::FETCH_OBJ);
        $ordersLength = count($orders);
        if ($ordersLength !=0) {
            return $orders;
        } else {            
            return false; 
        }
        
    }

    public function SetData($order)
    {
        $this->id = uniqid();
        $this->user = $order->user;
        $this->product_id = $order->product_id;  
        $this->hours = $order->hours;     
        return $this;    
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
    function CreateOrder($order);
    
    function GetOrder($id);

    function DeleteOrder($id);

    function ShowOrders();

    function SetData($order);

    function CheckData($order);

    function FindOrder($order);
}

?>