$('#phone-number').inputmask('(999)-999-99-99');
$('#btn-registration').click(function () {  
$('.alert').remove();
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
            if (error.message === 'Empty Data Error') {
                if (user.login === undefined || user.login === null || user.login.length === 0) {
                    $('#user-login').toggleClass('is-invalid');
                    $('#user-login').after('<div class="invalid-feedback">Вы не ввели логин!</div>');
                } else {
                    $('#user-login').toggleClass('is-valid');
                    $('#user-login').after('<div class="invalid-feedback">Вы не ввели логин!</div>');
                }

                if (user.pass === undefined || user.pass === null || user.pass.length === 0) {
                    $('#user-password').toggleClass('is-invalid');
                    $('#user-password').after('<div class="invalid-feedback">Вы не ввели пароль!</div>');
                }

                if (user.phoneNumber === undefined || user.phoneNumber === null || user.phoneNumber.length == 0) {
                    $('#phone-number').toggleClass('is-invalid');
                    $('#phone-number').after('<div class="invalid-feedback">Вы не ввели номер телефона!</div>');
                }

                if (user.lastName === undefined || user.lastName === null || user.lastName.length === 0) {
                    $('#user-last-name').toggleClass('is-invalid');
                    $('#user-last-name').after('<div class="invalid-feedback">Вы не ввели фамилию!</div>');
                }

                if (user.firstName === undefined || user.firstName === null || user.firstName.length === 0) {
                    $('#user-first-name').toggleClass('is-invalid');
                    $('#user-first-name').after('<div class="invalid-feedback">Вы не ввели имя!</div>');
                }

                if (user.middleName === undefined || user.middleName === null || user.middleName.length === 0) {
                    $('#user-middle-name').toggleClass('is-invalid');
                    $('#user-middle-name').after('<div class="invalid-feedback">Вы не ввели отчество!</div>');
                }

                if (confirmPass === undefined || confirmPass === null || confirmPass.length === 0) {
                    $('#user-confirm-password').toggleClass('is-invalid');
                    $('#user-confirm-password').after('<div class="invalid-feedback">Вы не подтвердили пароль!</div>');
                }
            }
            
            if (error.message === 'Length Data Error') {
                
                if (user.login.length < 6 || user.login.length > 24) {
                    $('#user-login').toggleClass('is-invalid');
                    $('#user-login').after('<div class="invalid-feedback">Длина логина должна быть от 6 до 24 символов!</div>');
                }

                if (user.pass.length < 6 || user.pass.length > 24) {
                    $('#user-password').toggleClass('is-invalid');
                    $('#user-password').after('<div class="invalid-feedback">Длина пароля должна быть от 6 до 24 символов!</div>');
                }

                if (user.phoneNumber.length != 15) {
                    $('#phone-number').toggleClass('is-invalid');
                    $('#phone-number').after('<div class="invalid-feedback">Наш сервис работает только с операторами РФ!</div>');
                }

                if (user.lastName.length < 3 || user.lastName.length > 30) {
                    $('#user-last-name').toggleClass('is-invalid');
                    $('#user-last-name').after('<div class="invalid-feedback">Длина фамилии должна быть от 3 до 30 символов!</div>');
                }

                if (user.firstName.length < 4 || user.firstName.length > 15) {
                    $('#user-first-name').toggleClass('is-invalid');
                    $('#user-first-name').after('<div class="invalid-feedback">Длина имени должна быть от 4 до 15 символов!</div>');                    
                }

                if (user.middleName.length < 6 || user.middleName.length > 24) {
                    $('#user-middle-name').toggleClass('is-invalid');
                    $('#user-middle-name').after('<div class="invalid-feedback">Длина отчества должна быть от 6 до 24 символов!</div>');
                }
                
            }
            
            if (error.message === 'Wrong Data Error') {
                if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) === null)){
                    $('#user-login').toggleClass('is-invalid');
                    $('#user-login').after('<div class="invalid-feedback">Логин должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
                } else {
                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] !== user.login) {
                        $('#user-login').toggleClass('is-invalid');
                        $('#user-login').after('<div class="invalid-feedback">Логин должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
                    }
                }
                
                if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass) === null || (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.pass)[0] !== user.pass)){
                    $('#user-password').toggleClass('is-invalid');
                    $('#user-password').after('<div class="invalid-feedback">Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
                }

                if ((/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber)) === null || (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(user.phoneNumber)[0] !== user.phoneNumber)) {
                    $('#phone-number').toggleClass('is-invalid');
                    $('#phone-number').after('<div class="invalid-feedback">Наш сервис работает только с операторами РФ!</div>');
                }

                if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName) === null) {
                    $('#user-last-name').toggleClass('is-invalid');
                    $('#user-last-name').after('<div class="invalid-feedback">Фамилия должна состоять из латинских букв или кириллистических букв!</div>');
                } else {
                    if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName)[0] !== user.lastName) {
                        $('#user-last-name').toggleClass('is-invalid');
                        $('#user-last-name').after('<div class="invalid-feedback">Фамилия должна состоять из латинских букв или кириллистических букв!</div>');
                    }
                }

                if ((/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName) === null) || (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName)[0] != user.firstName)) {
                    $('#user-first-name').toggleClass('is-invalid');
                    $('#user-first-name').after('<div class="invalid-feedback">Имя должно состоять из латинских букв или кириллистических букв!</div>');
                }

                if ((/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName) === null) || (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName)[0] !== user.middleName)) {
                    $('#user-middle-name').toggleClass('is-invalid');
                    $('#user-middle-name').after('<div class="invalid-feedback">Отчество должно состоять из латинских букв или кириллистических букв!</div>');
                }
            }

            if (error.message === 'Uncomparable Passwords Error') {
                alert("Пароли не совпадают!");
            }
        }
    }
})
