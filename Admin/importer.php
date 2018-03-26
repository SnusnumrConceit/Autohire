<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SERVER['HTTP_REFERER'] ?? '') {
        $url = $_SERVER['HTTP_REFERER'];
        function CreateCSV($fileName, $data)
        {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="{$fileName}.csv";');
            
            $file = fopen("{$fileName}.csv", 'w');
            //$fileThread = fopen('php://output', 'w');

            foreach ($data as $key => $value) {
                fputcsv($file, $value, ",");
            }

            fclose($file);
        }

        if (strripos($url, 'users')) {
            require_once '../DbConnect.php';
            $db = DbConnect();    
            $selectUsersQuery = $db->prepare("SELECT Login, LName, FName, MName FROM users");
            $selectUsersQuery->execute();
            $users = $selectUsersQuery->fetchAll(PDO::FETCH_ASSOC); 

            CreateCSV('users', $users);
            
        } else {
            echo("Пришло что-то другое");
        }
        
        

        
    } else {
        echo("Ошибка! Данные не пришли!");
    }
} else {
    echo("Ошибка! Запрос не прошёл!");
}

    



?>