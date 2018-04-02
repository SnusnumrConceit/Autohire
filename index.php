<?php
    session_start();
    $_SESSION['name'] = 'user';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>АвтоПрокат</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Styles/index.css">
    </head>
    <body>
        <?php
            require_once 'header.php';
            
            require_once 'Classes/Product.php';
            print "<main class=\"container\">";
            $product = new Product();
            $products = $product->ShowProducts();
            if ($products) {
                $productsLength = count($products);
                //типа оставшиеся товары
                $lostProducts = $productsLength;
                for ($i=0; $i < $productsLength;) {
                    if ($lostProducts % 3 == 0) {
                        print "<div class=\"row\">
                                <div class=\"card col-4\">
                                    <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i]->Photo}\">
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">{$products[$i]->Brand} {$products[$i]->Model}</h5>
                                        <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i]->id}\">Подробнее</a>
                                    </div>
                                </div>
                                <div class=\"card col-4\">
                                    <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i+1]->Photo}\">
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">{$products[$i]->Brand} {$products[$i+1]->Model}</h5>
                                        <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i+1]->id}\">Подробнее</a>
                                    </div>
                                </div>
                                <div class=\"card col-4\">
                                    <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i+2]->Photo}\">
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">{$products[$i+2]->Brand} {$products[$i+2]->Model}</h5>
                                        <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i+2]->id}\">Подробнее</a>
                                    </div>
                                </div>
                            </div>";    
                        $lostProducts -= 3;   
                        $i += 3;                         
                    }
                    elseif ($lostProducts %2 == 0) {
                        print "<div class=\"row\">
                            <div class=\"card col-4 offset-sm-2\">
                                <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i]->Photo}\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">{$products[$i]->Brand} {$products[$i]->Model}</h5>
                                    <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i]->id}\">Подробнее</a>
                                </div>
                            </div>
                            <div class=\"card col-4\">
                                <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i+1]->Photo}\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">{$products[$i]->Brand} {$products[$i+1]->Model}</h5>
                                    <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i+1]->id}\">Подробнее</a>
                                </div>
                            </div>
                            </div>";
                            $lostProducts -= 2;   
                            $i += 2;
                    }
                    else {
                        print "<div class=\"row\">
                            <div class=\"card col-4 offset-sm-2\">
                                <img class=\"card-img-top\" src=\"data:image/jpeg;base64,{$products[$i]->Photo}\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">{$products[$i]->Brand} {$products[$i]->Model}</h5>
                                    <a class=\"btn btn-primary\" href=\"car.php?car={$products[$i]->id}\">Подробнее</a>
                                </div>
                            </div>
                            </div>";
                            $i++;
                    }

                }
            }
            print "</main>";
            require_once 'footer.php';
        ?>

        
        
    </body>
</html>