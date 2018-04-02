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
                    
                    $order->CreateOrder($order);
                    setcookie("Order[{$order->id}]", md5($order->product_id), time() - 3600, '/');
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
                    <div class="photo-container col-sm-8"><img class="img-fluid" src="data:image/jpg;base64,{$product[0]->Photo}"></div>
                    <div class="functional-container col">
                        <form method="POST">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="hours">Количество часов</label>
                                    <input type="number" class="form-control" id="hours" value="1">
                                </div>
                                <div class="form-group col">
                                    <span>Цена: {$product[0]->Price} руб./ч</span>
                                    <button type="button" class="btn btn-primary" id="btn-rent">В аренду</button>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="row">
                    <nav class="col-sm-12">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#main-characteristics">Характеристики</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#addictive-characteristics">Дополнительные</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="tab-content">
                    <div id="main-characteristics" class="tab-pane active container">
                        <ul>
                            <li>Кузов: {$product[0]->Type}</li>
                            <li>Коробка передач: {$product[0]->Transmission}</li>
                            <li>Топливо:{$product[0]->Oil}</li>
                            <li>Привод: {$product[0]->Control}</li>
                        </ul>
                    </div>
                    <div id="addictive-characteristics" class="tab-pane container fade">
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
        </div>";
        require_once 'footer.php';
    print "<script src=\"Scripts/car_scripts.js\"></script>
    </body>
</html>";

}
?>