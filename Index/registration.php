<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        if ($_POST['user'] ?? '') {
            $inputData = json_decode($_POST['user']);                        
            require_once '../Classes/User.php';
            $user = new User();
            if($user->CheckData($inputData)) {
                $user = $user->SetData($inputData, $user);
                $user->CreateUser($user);
            }            
        }
    }
?>