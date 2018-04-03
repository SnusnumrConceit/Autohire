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
                }
            }
        } else {
            echo("Для аренды автомобиля войдите в аккаунт!");
        }
        
        
    }
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
print "<!DOCTYPE html>
<html lang='en'>
    <head>
        <title></title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css'>
        <link rel='stylesheet' href='Styles/index.css'>
    </head>
    <body>
        <div class='container-fluid'>";
        require_once 'header.php';
        print "<div class='container' id='main'>";
            if ($_GET['car'] ?? '') {
                $id = $_GET['car'];
                require_once 'Classes/Product.php';
                $product = new Product();
                $product = $product->GetProduct($id);
                if ($product) {
print <<<PRODUCT
                <div class="row">
                    <div class="row">
                        <div class="photo-container col">
                            <img class="img-fluid" src="data:image/jpg;base64,{$product[0]->Photo}">
                        </div>
                    </div>
                    <div class="row">
                        <form method="POST" class='form-inline offset-sm-3 col' style="margin-bottom: 60px; margin-top: 30px;">
                            <div class="form-group">
                                    <label class="col-form-label col-4" for="hours">Количество часов</label>
                                    <input type="number" class="form-control col-2" id="hours" value="1">
                                    <span class="offset-sm-1" style="margin-right:15px;">Цена: {$product[0]->Price} руб./ч</span>
                                    <button type="button" class="btn btn-primary" id="btn-rent">В аренду</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <div class="row">
                    <nav class="col">
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
                <div class="tab-content row">
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
        </div>
        </div>";
        require_once 'footer.php';
    print "<script src='Scripts/car_scripts.js'></script>
    </body>
</html>";

}
?>