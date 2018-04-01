$('#phone-number').inputmask('(999)-999-99-99');
$('#btn-registration').click(function () {  
var login = $('#user-login').val(),
        pass = $('#user-password').val(),
        confirmPass = $('#user-confirm-password').val(),
        lastName = $('#user-last-name').val(),
        firstName = $('#user-first-name').val(),
        middleName = $('#user-middle-name').val(),
        phoneNumber = $('#phone-number').val(),
        user = new User(login, pass, lastName, firstName, middleName, phoneNumber);
        if (CheckData(user)) {
            user = JSON.stringify(user);
            $.post('admin/users.php', {user: user}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();                
                }
            })
        }

    function User(login, pass, lastName, firstName, middleName, phoneNumber) { 
        this.login = login,
        this.pass = pass,
        this.lastName = lastName,
        this.firstName = firstName,
        this.middleName = middleName,
        this.phoneNumber = phoneNumber
     }

    function CheckData(user) {  
        try {
            if (user.login !== undefined && user.login !== null && user.login.length !== 0 &&
                user.pass !== undefined && user.pass !== null && user.pass.length !== 0 &&
                user.lastName !== undefined && user.lastName !== null && user.lastName.length !== 0 &&
                user.firstName !== undefined && user.firstName !== null && user.firstName.length !== 0 &&
                user.middleName !== undefined && user.middleName !== null && user.middleName.length !== 0 &&
                confirmPass !== undefined && confirmPass !== null && confirmPass.length !== 0 &&
                user.phoneNumber !== undefined && user.phoneNumber !== null && user.phoneNumber.length !== 0) {
                    if (user.login.length >= 6 && user.login.length <= 24 &&
                        user.pass.length >= 6 && user.pass.length <= 24 &&
                        user.lastName.length >= 3 && user.lastName.length <= 30 &&
                        user.firstName.length >= 4 && user.firstName.length <= 15 &&
                        user.middleName.length >= 6 && user.middleName.length <= 24 &&
                        user.phoneNumber.length == 15) {
                            if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) !== null)
                                && (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass) !== null)
                                && (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName) !== null)
                                && (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName) !== null)
                                && (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName) !== null)
                                && (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber) !== null)) {
                                    if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] === user.login)
                                        && (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass)[0] === user.pass)
                                        && (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName)[0] === user.lastName)
                                        && (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName)[0] === user.firstName)
                                        && (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName)[0] === user.middleName)
                                        && (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber)[0] === user.phoneNumber)) {
                                            if (user.pass === confirmPass) {
                                                return true;    
                                            } else {
                                                throw new Error('Uncomparable Passwords Error');
                                            }
                                    } else {
                                        throw new Error('Wrong Data Error');
                                    }   
                            } else {
                                throw new Error('Wrong Data Error');
                            }                            
                    } else {
                        throw new Error('Length Data Error');
                    }
            } else {
                throw new Error('Empty Data Error');
            }
        } catch (error) {
            console.log(/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login));
                console.log(/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass));
                console.log(/[A-ZА-ЯЁ]{1}[a-zа-яё]{2,}/.exec(user.lastName));
                console.log(/[A-ZА-ЯЁ]{1}[a-zа-яё]{3,}/.exec(user.firstName));
                console.log(/[A-ZА-ЯЁ]{1}[a-zа-яё]{5,}/.exec(user.middleName));
                console.log('-------------------------------------------------');
            if (error.message === 'Empty Data Error') {
                if (user.login === undefined || user.login === null || user.login.length === 0) {
                    alert('Вы не ввели логин!');
                }

                if (user.pass === undefined || user.pass === null || user.pass.length === 0) {
                    alert('Вы не ввели пароль!');
                }

                if (user.phoneNumber === undefined || user.phoneNumber === null || user.phoneNumber.length == 0) {
                    alert('Вы не ввели номер телефона!');
                }

                if (user.lastName === undefined || user.lastName === null || user.lastName.length === 0) {
                    alert('Вы не ввели фамилию!');
                }

                if (user.firstName === undefined || user.firstName === null || user.firstName.length === 0) {
                    alert('Вы не ввели имя!');
                }

                if (user.middleName === undefined || user.middleName === null || user.middleName.length === 0) {
                    alert('Вы не ввели отчество!');
                }

                if (confirmPass === undefined || confirmPass === null || confirmPass.length === 0) {
                    alert('Вы не подтвердили пароль!');
                }
            }
            
            if (error.message === 'Length Data Error') {
                
                if (user.login.length < 6 || user.login.length > 24) {
                    alert('Длина логина должна быть от 6 до 24 символов!');
                }

                if (user.pass.length < 6 || user.pass.length > 24) {
                    alert('Длина пароля должна быть от 6 до 24 символов!');
                }

                if (user.phoneNumber.length != 15) {
                    alert('Наш сервис работает только с телефоннами номерами РФ!');
                }

                if (user.lastName.length < 3 || user.lastName.length > 30) {
                    alert('Длина фамилии должна быть от 3 до 30 символов!');
                }

                if (user.firstName.length < 4 || user.firstName.length > 15) {
                    alert('Длина имени должна быть от 4 до 15 символов!');
                }

                if (user.middleName.length < 6 || user.middleName.length > 24) {
                    alert('Длина отчества должна быть от 6 до 24 символов!');
                }
                
            }
            
            if (error.message === 'Wrong Data Error') {
                if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) === null)){                    
                    alert('Логин должен состоять из латинских букв, точки и нижнего подчёркивания!');
                } else {
                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] !== user.login) {
                        alert('Логин должен состоять из латинских букв, точки и нижнего подчёркивания!');
                    }
                }
                
                if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass) === null || (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass)[0] !== user.pass)){
                    alert('Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!');
                }

                if ((/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber)) === null || (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber)[0] !== user.phoneNumber)) {
                    alert('Наш сервис работает только с телефоннами номерами РФ!');
                }

                if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName) === null) {
                    alert('Фамилия должна состоять из латинских букв или кириллистических букв!');
                } else {
                    if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName)[0] !== user.lastName) {
                        alert('Фамилия должна состоять из латинских букв или кириллистических букв!');
                    }
                }

                if ((/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName) === null) || (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName)[0] != user.firstName)) {
                    alert('Имя должно состоять из латинских букв или кириллистических букв!');
                }

                if ((/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName) === null) || (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName)[0] !== user.middleName)) {
                    alert('Отчество должно состоять из латинских букв или кириллистических букв!')
                }
            }

            if (error.message === 'Uncomparable Passwords Error') {
                alert("Пароли не совпадают!");
            }
        }
    }
})
