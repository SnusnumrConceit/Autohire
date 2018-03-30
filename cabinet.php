<?php
    session_start();
    if ($_COOKIE['Account'] ?? '') {
    
print <<<USER_CABINET
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>        
USER_CABINET;
        require_once 'header.php';
        print "<div class=\"container\">";
        foreach ($_COOKIE['Account'] as $key => $value) {
            $user_id = $key;
        }
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findOrderQuery = $db->prepare("SELECT ord.id, concat(u.LName, ' ', U.FName, ' ', u.MName) AS User, m.Title AS Model, cb.Type, pr.Price FROM orders AS ord INNER JOIN users AS u ON ord.User_id = u.id INNER JOIN products AS pr ON ord.Product_id = pr.id INNER JOIN models AS m ON pr.Model_id = m.id INNER JOIN carbodies AS cb ON pr.CarBody_id = cb.id WHERE u.id = ?");
        $findOrderQuery->execute(array($user_id));
        $order = $findOrderQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($order) == 1) {
            print "<h2>{$order[0]->User}</h2>
                    <table class=\"table table-bordered\">
                                        <thead>
                                            <th>id</th>
                                            <th>Модель</th>
                                            <th>Кузов</th>
                                            <th>Цена</th>
                                            <th></th>
                                        </thead>
                                        <tbody>";
            for ($i=0; $i < count($order); $i++) { 
                        print "<tr>
                                    <td>{$order[$i]->id}</td>
                                    <td>{$order[$i]->Model}</td>
                                    <td>{$order[$i]->Type}</td>
                                    <td>{$order[$i]->Price}</td>
                                    <td><button class=\"btn btn-danger\">Удалить</button></td>
                                </tr>";
            }
            print "</tbody>
            </table>
            <h3>Итого: {$order[0]->Price} руб</h3>";
        } else {
            echo("Вы не арендовали ни одного автомобиля!");
        }
    print "</div>";
    require_once 'footer.php';
    print "</body>
</html>";

    } else {
        header('location: ../index.php');
    }
?>