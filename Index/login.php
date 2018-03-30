<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['user'] ?? '') {
            $user = json_decode($_POST['user']);
            function GetUserByLogin($user)
            {
                require_once '../DbConnect.php';
                $db = DbConnect();
                $userQuery = $db->prepare("SELECT * FROM users WHERE Login = ?");
                $userQuery->execute(array($user->login));
                $findlessUser = $userQuery->fetchAll(PDO::FETCH_OBJ);
                if (count($findlessUser) == 1) {
                    if (password_verify($user->password, $findlessUser[0]->Password)) {
                        return $findlessUser;
                    } else {
                        return false;
                    }
                    
                } else {
                    return false;
                }
                
            }
            function CheckData($user)
            {
                try {
                    if (($user->login ?? '') && ($user->password ?? '')) {
                        
                        $loginLength = strlen($user->login);
                        $passLength = strlen($user->password);
                        if (($loginLength <= 24 && $loginLength >= 6) && ($passLength >= 6 && $passLength <=24)) {
                            
                            if (trim($user->login) === $user->login && htmlentities($user->login) === $user->login &&
                                trim($user->password) === $user->password && htmlentities($user->password) === $user->password) {
                                    
                                #проверка на соответствие регуляркам                                        
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->login, $regLogin);
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->password, $regPass);
                                    if (($regLogin ?? '') && ($regPass ?? '')) {
                                        if ($regLogin[0] == $user->login &&
                                            $regPass[0] == $user->password) {
                                                $user = GetUserByLogin($user);
                                                if ($user) {
                                                    return $user;
                                                } else {
                                                    throw new Exception("Database Data Error", 1);
                                                }
                                            
                                        } else {
                                            throw new Exception("Wrong Data Error", 1);
                                        }
                                    
                                    } else {
                                        throw new Exception("Wrong Data Error", 1);
                                    }
                                
                            } else {
                                throw new Exception("Wrong Data Error", 1);
                                
                            }
                            
                        } else {
                            throw new Exception("Length Data Error", 1);
                        }
                        
                    } else {
                        throw new Exception("Empty Data Error", 1);
                    }
                } catch (Exception $error) {
                    if ($error->getMessage() === 'Empty Data Error') {
                        if ($user->login ?? '') {
                            echo("Вы не ввели логин!");
                        }
                        if ($user->password ?? '') {
                            echo("Вы не ввели пароль!");
                        }
                        return false;
                    }
                    
                    if ($error->getMessage() === 'Length Data Error') {
                        if ($loginLength < 6 || $loginLength > 24) {
                            echo("Длина логина должна быть от 6 до 24 символов!");
                        }
                        if ($passLength < 6 || $passLength > 24) {
                            echo("Пароль логина должна быть от 6 до 24 символов!");
                        }
                    }

                    if ($error->getMessage() === 'Wrong Data Error') {
                        if (trim($user->login) !== $user->login || htmlentities($user->login) !== $user->login || !($regLogin ?? '')){
                            echo("Логин должен состоять из латинских букв, точки и нижнего подчёркивания!");
                        }
                        if ($regLogin ?? '') {
                            if ($regLogin[0] != $user->login) {
                                echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                            } 
                        }
                        if (trim($user->password) !== $user->password || htmlentities($user->password) !== $user->password || !($regLogin ?? '')){
                            echo("Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!");
                        }
                        if ($regPass ?? '') {
                            if ($regPass[0] != $user->password) {
                                echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                            } 
                        }
                    }

                    if ($error->getMessage() === 'Database Data Error') {
                        echo("Вы ввели неверные данные!");
                    }
                    return false;
                }
            }
        } else {
            echo("Приносим свои извинения! Неполадки на сервере");
        }
        $user = CheckData($user);
        if($user){
            require_once '../Authentication.php';
            Authenticate($user);
        }
    } else {
        http_response_code(502);
    }
    

?>