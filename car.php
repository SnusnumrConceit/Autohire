<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_COOKIE['Account'] ?? '') {
            if ($_POST['order'] ?? '') {
                foreach ($_COOKIE['Account'] as $key => $value) {
                    $user_id = $key;
                }
                $inputData = json_decode($_POST['order']);
                $inputData->user = $user_id;
                require_once 'Classes/Order.php';

                $order = new Order();
                if($order->CheckData($inputData)){
                    $order = $order->SetData($inputData, $order);
                    require_once 'DbConnect.php';
                    $db = DbConnect();
                    $order->CreateOrder($order, $db);
                }
            }
        } else {
            echo("Для аренды автомобиля войдите в аккаунт!");
        }
        
        
    }
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
print "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <title></title>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css\">
    </head>
    <body>";
        require_once 'header.php';
        print "<div class=\"container\">";
            if ($_GET['car'] ?? '') {
                $id = $_GET['car'];
                require_once 'Classes/Product.php';
                $product = new Product();
                $product = $product->GetProduct($id);
                if ($product) {
print <<<PRODUCT
                <div class="row"
                    <div class="photo-container col-sm-8"><img src="data:image/jpg;base64,{$product[0]->Photo}"></div>
                    <div class="functional-container">
                        <form method="POST">
                            <div class="form-group">
                                <label for="hours">Количество часов</label>
                                <input type="number" class="form-control" id="hours" value="1">
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Цена: {$product[0]->Price} руб./ч</span>
                                <button type="button" class="btn btn-primary" id="btn-rent">В аренду</button>
                            </div>
                        </form>
                    </div>
                <div class="row">
                    <nav class="navbar-nav">
                        <ul>
                            <li>Характеристики</li>
                            <li>Дополнительные</li>
                    </nav>
                </div>
                <div class="row">
                    <div id="main-characteristics">
                        <ul>
                            <li>Кузов: {$product[0]->Type}</li>
                            <li>Коробка передач: {$product[0]->Transmission}</li>
                            <li>Топливо:{$product[0]->Oil}</li>
                            <li>Привод: {$product[0]->Control}</li>
                        </ul>
                    </div>
                    <div id="addictive-characteristics">
PRODUCT;
                        if ($product[0]->Options ?? '') {
                                   print "<ul>";
                                   for ($j=0; $j < count($product[0]->Options); $j++) { 
                                        print "<li>{$product[0]->Options[$j]->Title}</li>";
                                    }
                                    print "</ul>";
                        } else {
                            echo("<p>Дополнительные опции отсутствуют</p>");
                        }
            }
}
                
           print     "</div>
            </div>
        </div>
        </div>
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
        <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js\" integrity=\"sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn\" crossorigin=\"anonymous\"></script>
        <script src=\"Scripts/registration_scripts.js\"></script>
        <script src=\"Scripts/login_scripts.js\"></script>
        <script src=\"Scripts/car_scripts.js\"></script> 
    </body>
</html>";

}
?>