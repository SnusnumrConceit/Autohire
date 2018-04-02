<?php
    function DbConnect (){
        try { 
        
        $db = new PDO("mysql:host=localhost;charset=utf8;dbname=autohire", 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch (PDOException $e) {
            echo('Не могу подключиться к базе данных: '.$e->getMessage());
        }
        return $db;

    }
?>