<?php
//класс, отвечающий за пользователей
class User implements IUser{
    private $id;
    private $login;
    private $password;
    private $lastName;
    private $firstName;
    private $middleName;
    private $role;

    public function CreateUser($user)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $user, 'create')) {
            $createUserQuery = $db->prepare("INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?)");
            $createUserQuery->execute(array($user->id, $user->login, $user->password, $user->lastName, $user->firstName, $user->middleName, 1));
        } 
    }

    public function DeleteUser($id)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $deleteUserQuery = $db->prepare("DELETE FROM users WHERE id = ?");
        $deleteUserQuery->execute(array($id));        
    }

    public function GetUser($id)
    {
        if (substr($_SERVER['HTTP_REFERER'], -9, 9) === 'index.php') {
            require_once 'DbConnect.php';    
        } else {//if (substr($_SERVER['HTTP_REFERER'], -12, 12) === 'userinfo.php') {
            require_once '../../DbConnect.php';
        }
        $db = DbConnect();
        $getUserQuery = $db->prepare('SELECT * FROM users WHERE id = ?');
        $getUserQuery->execute(array($id));
        $selectedUserQuery = $getUserQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($selectedUserQuery) == 1)   {
            return $selectedUserQuery;
        } else {
            echo("Данный кузов не найден");
        }
        
    }

    public function FindUser($lastName)
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $findUserQuery = $db->prepare('SELECT * FROM users WHERE LName = ?');
        $findUserQuery->execute(array($lastName));
        $currentUser = $findUserQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($currentUser) != 0) {
            return $currentUser;
        } else {
            return false;
        }
         
    }
    public function UpdateUser($user)
    {
        require_once '../../DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $user, 'update')) {
            $updateUserQuery = $db->prepare("UPDATE users SET Login = ?, LName = ?, FName = ?, MName = ? WHERE id = ?");
            $updateUserQuery->execute(array($user->login, $user->lastName, $user->firstName, $user->middleName, $user->id));            
        }
    }

    public function SetData($inputData, $user)
    {
        $user->id = uniqid();
        $user->login = $inputData->login;
        $user->password = password_hash($inputData->pass, PASSWORD_DEFAULT);
        ;;
        $user->lastName = $inputData->lastName;
        $user->firstName = $inputData->firstName;
        $user->middleName = $inputData->middleName;
        return $user;
    }

    public function CheckData($inputData)
    {
        try {
            $user = $inputData;             
            #проверка на обязательность поля
            if (strlen($user->login) != 0 && strlen($user->pass) != 0 && strlen($user->lastName) != 0 && 
            strlen($user->firstName) != 0 && strlen($user->middleName) !=0) {                
                $loginLength = strlen($user->login);
                $passLength = strlen($user->pass);
                $firstNameLength = strlen($user->firstName);
                $middleNameLength = strlen($user->middleName);
                $lastNameLength = strlen($user->lastName);
                #проверка на длину поля
                if ($loginLength >= 6 && $loginLength <= 24 &&
                    $passLength >= 6 && $passLength <= 24 &&
                    $firstNameLength >= 4 && $firstNameLength <= 15 &&
                    $lastNameLength >= 3 && $lastNameLength <= 30 &&
                    $middleNameLength >=6 && $middleNameLength <= 24) {
                        #проверка на наличие XSS-атаки в поле
                        if (htmlspecialchars($user->login) == $user->login &&
                            htmlspecialchars($user->pass) == $user->pass &&
                            htmlspecialchars($user->lastName) == $user->lastName &&
                            htmlspecialchars($user->firstName) == $user->firstName &&
                            htmlspecialchars($user->middleName) == $user->middleName) {
                                #проверка на наличие пробелов в поле
                                if (trim($user->login) == $user->login &&
                                    trim($user->pass) == $user->pass &&
                                    trim($user->lastName) == $user->lastName &&
                                    trim($user->firstName) == $user->firstName &&
                                    trim($user->middleName) == $user->middleName) {                                        
                                        #проверка на соответствие регуляркам                                        
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->login, $regLogin);
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->pass, $regPass);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{2,}/u', $user->lastName, $regLastName);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{3,}/u', $user->firstName, $regFirstName);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{5,}/u', $user->middleName, $regMiddleName);
                                        
                                        if (($regLogin ?? '') && ($regPass ?? '') && ($regLastName ?? '') && ($regFirstName ?? '') && ($regMiddleName ?? '')) {
                                            if ($regLogin[0] == $user->login &&
                                            $regPass[0] == $user->pass &&                                            
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
                if (strlen($user->pass) == 0) {
                    echo("Поле Пароль является обязательным \n");                    
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
                if (strlen($user->pass) < 6 || strlen($user->pass) > 24) {
                    echo("Пароль должен быть от 6 до 24 символов \n");                    
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
                if (htmlspecialchars($user->login) != $user->login && trim($user->login) != $user->login || !($regLogin ?? '')) {
                    if ($regLogin[0] != $user->login) {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } else {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    }                                   
                }
                if ($regLogin ?? '') {
                    if ($regLogin[0] != $user->login) {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } 
                }
                if (htmlspecialchars($user->pass) != $user->pass && trim($user->pass) != $user->pass || !($regPass ?? '')) {
                    if ($regPass[0] != $user->pass) {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } else {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    }                    
                }
                if ($regPass ?? '') {
                    if ($regPass[0] != $user->pass) {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } 
                }
                if (htmlspecialchars($user->lastName) != $user->lastName && trim($user->lastName) != $user->lastName || !($regLastName ?? '')) {
                    if (($regLastName[0] ?? '') && $regLastName[0] == $user->lastName) {
                        echo("Фамилия должна состоять из латинских или кириллистических букв \n");
                    } else {
                        echo("Фамилия должна состоять из латинских или кириллистических букв \n");             
                    }
                }
                if ($regLastName ?? '') {
                    if ($regLastName[0] != $user->lastName) {
                        echo("Фамилия должна состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } 
                }
                if (htmlspecialchars($user->firstName) != $user->firstName && trim($user->firstName) != $user->firstName || !($regFirstName ?? '')) {
                    if (($regFirstName[0] ?? '') && $regFirstName[0] == $user->firstName) {
                        echo("Имя должно состоять из латинских или кириллистических букв \n");
                    } else {
                        echo("Имя должно состоять из латинских или кириллистических букв \n");
                    }                    
                }
                if ($regFirstName ?? '') {
                    if ($regFirstName[0] != $user->firstName) {
                        echo("Имя должно состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } 
                }
                if (htmlspecialchars($user->middleName) != $user->middleName && trim($user->middleName) != $user->middleName || !($regMiddleName ?? '')) {
                    if (($regMiddleName[0] ?? '') && $regMiddleName[0] == $user->middleName) {
                        echo("Отчество должно состоять из латинских или кириллистических букв \n");
                    } else {
                        echo("Отчество должно состоять из латинских или кириллистических букв \n");
                    }                                        
                }
                if ($regMiddleName ?? '') {
                    if ($regMiddleName[0] != $user->middleName) {
                        echo("Отчество должно состоять из латинских букв, цифр, точки и знака подчёркивания \n");
                    } 
                }
                return false;
            }
        }
    }

    public function ShowUsers()
    {
        require_once '../DbConnect.php';
        $db = DbConnect();
        $selectUsersQuery = $db->prepare("SELECT * FROM users");
        $selectUsersQuery->execute();
        $users = $selectUsersQuery->fetchAll(PDO::FETCH_OBJ);
        $usersLength = count($users);
        if ($usersLength != 0) {
            return $users;
        } else {            
            return false;
        }
    }

    protected function CheckDublicates($db, $user, $pointer)
    {
        if ($pointer === 'create') {
            $getUserQuery = $db->prepare("SELECT * from users WHERE Login = ?");
            $getUserQuery->execute(array($user->login));
            $currentUser = $getUserQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentUser) == 0) {                
                return true;
            } else {
                echo ('Такой пользователь уже существует');
                return false;
            }            
        }
        elseif ($pointer === 'update') {
            $getUserQuery = $db->prepare("SELECT * from users WHERE Login = ?");
            $getUserQuery->execute(array($user->login));
            $currentUser = $getUserQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentUser) == 0 || count($currentUser) == 1) {
                return true;
            } else {
                echo ('Такой пользователь уже существует');
                return false;
            }            
        }
    }
}
    

interface IUser {
    function CreateUser($user);

    function DeleteUser($id);
    
    function GetUser($id);

    function FindUser($lastName);
  
    function UpdateUser($user);

    function SetData($inputData, $user);

    function CheckData($inputData);

    function ShowUsers();
}
    
?>