<?php
    if ($_COOKIE['Account'] ?? '') {
        foreach ($_COOKIE['Account'] as $key => $value) {
            $cookie_name = $key;
            $cookie_value = $value;
        }
        setcookie("Account[{$cookie_name}]", $cookie_value, time() - 3600, '/');
        header('location: ../index.php');
    } else {
        header('location: ../index.php');
    }
    
?>