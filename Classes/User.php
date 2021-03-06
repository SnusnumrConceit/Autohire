<?php
class User implements IUser{
    protected $id;
    protected $login;
    protected $password;
    protected $lastName;
    protected $firstName;
    protected $middleName;
    protected $role;

    public function CreateUser($user)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $user, 'create')) {
            $createUserQuery = $db->prepare("CALL spCreateUser (?, ?, ?, ?, ?, ?, ?)");
            $createUserQuery->execute(array($user->id, $user->login, $user->password, $user->lastName, $user->firstName, $user->middleName, $user->phoneNumber));
        }
        if (substr($_SERVER['HTTP_REFERER'], -9, 9) === 'index.php') {
            setcookie("Account[{$user->id}]", $user->password, time() + 3600, '/');
        }
    }

    public function DeleteUser($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteUserQuery = $db->prepare("CALL spDeleteUser(?)");
        $deleteUserQuery->execute(array($id));        
    }

    public function GetUser($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getUserQuery = $db->prepare('SELECT * FROM vusers WHERE id = ?');
        $getUserQuery->execute(array($id));
        $selectedUserQuery = $getUserQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($selectedUserQuery) == 1)   {
            return $selectedUserQuery;
        } else {
            return false;
        }
        
    }

    public function FindUser($lastName)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findUserQuery = $db->prepare('SELECT * FROM vusers WHERE LName = ?');
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
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($db, $user, 'update')) {
            $updateUserQuery = $db->prepare("CALL spUpdateUser(?, ?, ?, ?, ?)");
            $updateUserQuery->execute(array($user->login, $user->lastName, $user->firstName, $user->middleName, $user->id));            
        }
    }

    public function SetData($user)
    {
        $this->id = uniqid();
        $this->login = $user->login;
        $this->password = password_hash($user->pass, PASSWORD_DEFAULT);
        $this->lastName = $user->lastName;
        $this->firstName = $user->firstName;
        $this->middleName = $user->middleName;
        $this->phoneNumber = $user->phoneNumber;
        return $this;
    }

    public function CheckData($user)
    {
        try {
            #проверка на обязательность поля
            if (strlen($user->login) != 0 && strlen($user->pass) != 0 && strlen($user->lastName) != 0 && 
            strlen($user->firstName) != 0 && strlen($user->middleName) !=0 && strlen($user->phoneNumber) != 0) {                
                $loginLength = strlen($user->login);
                $passLength = strlen($user->pass);
                $firstNameLength = mb_strlen($user->firstName);
                $middleNameLength = mb_strlen($user->middleName);
                $lastNameLength = mb_strlen($user->lastName);
                $phoneNumberLength = strlen($user->phoneNumber);
                #проверка на длину поля
                if ($loginLength >= 6 && $loginLength <= 24 &&
                    $passLength >= 6 && $passLength <= 24 &&
                    $firstNameLength >= 4 && $firstNameLength <= 15 &&
                    $lastNameLength >= 3 && $lastNameLength <= 30 &&
                    $middleNameLength >=6 && $middleNameLength <= 24 &&
                    $phoneNumberLength == 15) {
                        #проверка на наличие XSS-атаки в поле
                        if (htmlspecialchars($user->login) == $user->login &&
                            htmlspecialchars($user->pass) == $user->pass &&
                            htmlspecialchars($user->lastName) == $user->lastName &&
                            htmlspecialchars($user->firstName) == $user->firstName &&
                            htmlspecialchars($user->middleName) == $user->middleName &&
                            htmlspecialchars($user->phoneNumber) == $user->phoneNumber) {
                                #проверка на наличие пробелов в поле
                                if (trim($user->login) == $user->login &&
                                    trim($user->pass) == $user->pass &&
                                    trim($user->lastName) == $user->lastName &&
                                    trim($user->firstName) == $user->firstName &&
                                    trim($user->middleName) == $user->middleName &&
                                    trim($user->phoneNumber) == $user->phoneNumber) {                                        
                                        #проверка на соответствие регуляркам                                        
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->login, $regLogin);
                                        preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $user->pass, $regPass);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{2,}/u', $user->lastName, $regLastName);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{3,}/u', $user->firstName, $regFirstName);
                                        preg_match('/[A-ZА-ЯЁ]{1}[a-zа-яё]{5,}/u', $user->middleName, $regMiddleName);
                                        preg_match('/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/', $user->phoneNumber, $regPhoneNumber);
                                        
                                        if (($regLogin ?? '') && ($regPass ?? '') && ($regLastName ?? '') && 
                                            ($regFirstName ?? '') && ($regMiddleName ?? '') && ($regPhoneNumber ?? '')) {
                                                if ($regLogin[0] == $user->login &&
                                                $regPass[0] == $user->pass &&                                            
                                                $regLastName[0] == $user->lastName &&
                                                $regFirstName[0] == $user->firstName &&
                                                $regMiddleName[0] == $user->middleName &&
                                                $regPhoneNumber[0] == $user->phoneNumber) {
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
                    echo("Вы не ввели пароль! \n");                                        
                } 
                if (strlen($user->pass) == 0) {
                    echo("Вы не ввели пароль! \n");                    
                } 
                if (mb_strlen($user->lastName) == 0) {
                    echo("Вы не ввели фамилию! \n");                    
                }
                if (mb_strlen($user->firstName) == 0) {
                    echo("Вы не ввели имя! \n");
                }
                if (mb_strlen($user->middleName) == 0) {
                    echo("Вы не ввели отчество!\n");
                }
                if(strlen($user->phoneNumber) == 0) {
                    echo('Вы не ввели номер телефона!');
                }
                return false;
            }
            if ($error->getMessage() === 'Length Data Error') {
                if (strlen($user->login) < 6 || strlen($user->login) > 24) {
                    echo("Логин должен быть от 6 до 24 символов! \n");                    
                } 
                if (strlen($user->pass) < 6 || strlen($user->pass) > 24) {
                    echo("Пароль должен быть от 6 до 24 символов! \n");                    
                } 
                if (strlen($user->lastName) < 3 || strlen($user->lastName) > 30) {
                    echo("Фамилия должна быть от 3 до 30 символов! \n");                    
                }
                if (strlen($user->firstName) < 4 || strlen($user->firstName) > 15 ) {
                    echo("Имя должно быть от 4 до 15 символов! \n");                    
                }
                if (strlen($user->middleName) < 6 || strlen($user->middleName) > 24) {
                    echo("Отчество должно быть от 6 до 24 символов! \n");                    
                }
                if ($phoneNumberLength != 15) {
                    echo('Наш сервис работает только с телефоннами номерами РФ!');
                }
                return false;
            }
            if ($error->getMessage() === 'Wrong Data Error') {
                if (htmlspecialchars($user->login) != $user->login && trim($user->login) != $user->login || !($regLogin ?? '')) {
                    if ($regLogin[0] != $user->login) {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    } else {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    }                                   
                }
                if ($regLogin ?? '') {
                    if ($regLogin[0] != $user->login) {
                        echo("Логин должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    } 
                }
                if (htmlspecialchars($user->pass) != $user->pass && trim($user->pass) != $user->pass || !($regPass ?? '')) {
                    if ($regPass[0] != $user->pass) {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    } else {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    }                    
                }
                if ($regPass ?? '') {
                    if ($regPass[0] != $user->pass) {
                        echo("Пароль должен состоять из латинских букв, цифр, точки и знака подчёркивания! \n");
                    } 
                }
                if (htmlspecialchars($user->lastName) != $user->lastName && trim($user->lastName) != $user->lastName || !($regLastName ?? '')) {
                    if (($regLastName[0] ?? '') && $regLastName[0] == $user->lastName) {
                        echo("Фамилия должна начинаться с заглавной буквы и состоять из латинских или кириллистических букв! \n");
                    } else {
                        echo("Фамилия должна начинаться с заглавной буквы и состоять из латинских или кириллистических букв! \n");           
                    }
                }
                if ($regLastName ?? '') {
                    if ($regLastName[0] != $user->lastName) {
                        echo("Фамилия должна начинаться с заглавной буквы и состоять из латинских или кириллистических букв! \n");
                    } 
                }
                if (htmlspecialchars($user->firstName) != $user->firstName && trim($user->firstName) != $user->firstName || !($regFirstName ?? '')) {
                    if (($regFirstName[0] ?? '') && $regFirstName[0] == $user->firstName) {
                        echo("Имя должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    } else {
                        echo("Имя должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    }                    
                }
                if ($regFirstName ?? '') {
                    if ($regFirstName[0] != $user->firstName) {
                        echo("Имя должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    } 
                }
                if (htmlspecialchars($user->middleName) != $user->middleName && trim($user->middleName) != $user->middleName || !($regMiddleName ?? '')) {
                    if (($regMiddleName[0] ?? '') && $regMiddleName[0] == $user->middleName) {
                        echo("Отчество должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    } else {
                        echo("Отчество должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    }                                        
                }
                if ($regMiddleName ?? '') {
                    if ($regMiddleName[0] != $user->middleName) {
                        echo("Отчество должно начинаться с заглавной буквы и состоять из латинских или кириллистических букв \n");
                    } 
                }

                if (htmlspecialchars($user->phoneNumber) != $user->phoneNumber && trim($user->phoneNumber) != $user->phoneNumber || !($regPhoneNumber ?? '')) {
                    if (($regPhoneNumber[0] ?? '') && $regPhoneNumber[0] == $user->phoneNumber) {
                        echo('Наш сервис работает только с телефоннами номерами РФ!');
                    } else {
                        echo('Наш сервис работает только с телефоннами номерами РФ!');
                    }                                        
                }
                if ($regPhoneNumber ?? '') {
                    if ($regPhoneNumber[0] != $user->phoneNumber) {
                        echo('Наш сервис работает только с телефоннами номерами РФ!');
                    } 
                }
                return false;
            }
        }
    }

    public function ShowUsers()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectUsersQuery = $db->prepare("SELECT * FROM vusers");
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
            $getUserQuery = $db->prepare("SELECT * from vusers WHERE Login = ?");
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
            $getUserQuery = $db->prepare("SELECT * from vusers WHERE Login = ?");
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

    function SetData($user);

    function CheckData($inputData);

    function ShowUsers();
}
    
?>