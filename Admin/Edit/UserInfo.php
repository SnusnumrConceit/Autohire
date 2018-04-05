<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['user'] ?? '') {
            require_once '../../Classes/User.php';
            $user = new User();
            $id = $_GET['user'];
            $user = $user->GetUser($id);
            if ($user ?? '') {                
                print <<<USER
<!DOCTYPE html>
<html>
    <head>
        <title>Пользователи</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet'' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css'>
    </head>
    <body>
         <div class="container">
                <a class="btn btn-default" href="../users.php">Назад</a>
                <h2>Пользователь {$user[0]->Login}</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login" value="{$user[0]->Login}">
                    </div>                    
                    <div class="form-group">
                        <label for="last-name">Фамилия</label>
                        <input type="text" class="form-control" id="last-name" value="{$user[0]->LName}">
                    </div>
                    <div class="form-group">
                        <label for="first-name">Имя</label>
                        <input type="text" class="form-control" id="first-name" value="{$user[0]->FName}">
                    </div>
                    <div class="form-group">
                        <label for="middle-name">Отчество</label>
                        <input type="text" class="form-control" id="middle-name" value="{$user[0]->MName}">
                    </div>
                    <button type="button" id="btnSubmitEdit" class="btn btn-success">Отправить</button>    
                </form>
            </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../../Scripts/edit_users_scripts.js"></script>      
    </body>
</html>

USER;

        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['edit_user'] ?? '') {
        function CheckData($user)
        {
            try {
                #проверка на обязательность поля
                if (strlen($user->login) != 0 && strlen($user->lastName) != 0 && 
                strlen($user->firstName) != 0 && strlen($user->middleName) !=0) {                
                    $loginLength = strlen($user->login);
                    $firstNameLength = mb_strlen($user->firstName);
                    $middleNameLength = mb_strlen($user->middleName);
                    $lastNameLength = mb_strlen($user->lastName);
                    #проверка на длину поля
                    if ($loginLength >= 6 && $loginLength <= 24 &&
                        $firstNameLength >= 4 && $firstNameLength <= 15 &&
                        $lastNameLength >= 3 && $lastNameLength <= 30 &&
                        $middleNameLength >=6 && $middleNameLength <= 24) {
                            #проверка на наличие XSS-атаки в поле
                            if (htmlspecialchars($user->login) == $user->login &&
                                htmlspecialchars($user->lastName) == $user->lastName &&
                                htmlspecialchars($user->firstName) == $user->firstName &&
                                htmlspecialchars($user->middleName) == $user->middleName) {
                                    #проверка на наличие пробелов в поле
                                    if (trim($user->login) == $user->login &&
                                        trim($user->lastName) == $user->lastName &&
                                        trim($user->firstName) == $user->firstName &&
                                        trim($user->middleName) == $user->middleName) {                                        
                                            #проверка на соответствие регуляркам                                        
                                            preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->login, $regLogin);                                            
                                            preg_match('/([А-Я][a-я]{2,})|([A-Z][a-z]{2,})/u', $user->lastName, $regLastName);
                                            preg_match('/([А-Я][a-я]{3,})|([A-Z][a-z]{3,})/u', $user->firstName, $regFirstName);
                                            preg_match('/([А-Я][a-я]{5,})|([A-Z][a-z]{5,})/u', $user->middleName, $regMiddleName);
                                            /*var_dump($regLogin);
                                            var_dump($regLastName);
                                            var_dump($regFirstName);
                                            var_dump($regMiddleName);*/
                                            
                                            if (($regLogin ?? '') && ($regLastName ?? '') && ($regFirstName ?? '') && ($regMiddleName ?? '')) {
                                                if ($regLogin[0] == $user->login &&
                                                $regLastName[0] == $user->lastName &&
                                                $regFirstName[0] == $user->firstName &&
                                                $regMiddleName[0] == $user->middleName) {
                                                    return true;
                                                } else {
                                                    throw new Exception('Wrong Data Error', 1);
                                                }    
                                            } else {
                                                throw new Exception('Wrong Data Error', 1);
                                            }    
                                                                        
                                    } else {
                                        throw new Exception('Wrong Data Error', 1);    
                                    }                        
                            } else {
                                throw new Exception('Wrong Data Error', 1);                        
                            }                    
                    } else {
                        throw new Exception('Length Data Error', 1);
                    }
                } else {
                    throw new Exception('Empty Data Error', 1);
                }
            }
            catch (Exception $error) {                            
                if ($error->getMessage() === 'Empty Data Error') {                
                    if (strlen($user->login) == 0) {                    
                        echo("Поле Логин является обязательным \n");                                        
                    } 

                    if (strlen($user->lastName) == 0) {
                        echo("Поле Фамилия является обязательным \n");                    
                    }

                    if (strlen($user->firstName) == 0) {
                        echo("Поле Имя является обязательным \n");
                    }

                    if (strlen($user->middleName) ==0) {
                        echo("Поле Отчество является обязательным \n");
                    }
                    return false;
                }
                if ($error->getMessage() === 'Length Data Error') {
                    if (strlen($user->login) < 6 || strlen($user->login) > 24) {
                        echo("Логин должен быть от 6 до 24 символов \n");                    
                    } 
                    
                    if (strlen($user->lastName) < 3 || strlen($user->lastName) > 30) {
                        echo("Фамилия должна быть от 3 до 30 символов \n");                    
                    }

                    if (strlen($user->firstName) < 4 || strlen($user->firstName) > 15 ) {
                        echo("Имя должно быть от 4 до 15 символов \n");                    
                    }

                    if (strlen($user->middleName) < 6 || strlen($user->middleName) > 24) {
                        echo("Отчество должно быть от 6 до 24 символов \n");                    
                    }
                    return false;
                }
                if ($error->getMessage() === 'Wrong Data Error') {
                    if (htmlspecialchars($user->login) != $user->login || trim($user->login) != $user->login || !($regLogin ?? '')) {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } elseif ($regLogin ?? '') {
                        if ($regLogin[0] != $user->login) {
                            echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                        } 
                    } else {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    }
                    
                    if (htmlspecialchars($user->lastName) != $user->lastName || trim($user->lastName) != $user->lastName || !($regLastName ?? '')) {
                            echo("Фамилия должна состоять из латинских или кириллистических букв \n");
                    } elseif ($regLastName ?? '') {
                        if ($regLastName[0] != $user->lastName) {
                            echo("Фамилия должна состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                        } 
                    } 

                    if (htmlspecialchars($user->firstName) != $user->firstName || trim($user->firstName) != $user->firstName || !($regFirstName ?? '')) {
                        echo("Имя должно состоять из латинских или кириллистических букв \n");
                    } elseif ($regFirstName ?? '') {
                        if ($regFirstName[0] != $user->firstName) {
                            echo("Имя должно состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                        } 
                    }

                    if (htmlspecialchars($user->middleName) != $user->middleName || trim($user->middleName) != $user->middleName || !($regMiddleName ?? '')) {
                        echo("Отчество должно состоять из латинских или кириллистических букв \n");
                    } elseif ($regMiddleName ?? '') {
                        if ($regMiddleName[0] != $user->middleName) {
                            echo("Отчество должно состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                        } 
                    }
                    return false;
                }
            }
        }

        $edit_user = json_decode($_POST['edit_user']);
        require_once '../../Classes/User.php';
        if (CheckData($edit_user)) {
            $user = new User();
            $user->UpdateUser($edit_user);
        }
        
    }
}

?>