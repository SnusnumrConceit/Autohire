$(document).ready(function () { 
    loginForm = $('#user-login'),
    passForm = $('#user-password'),
    confirmForm = $('#user-confirm-password'),
    lNameForm = $('#user-last-name'),
    fNameForm = $('#user-first-name'),
    mNameForm = $('#user-middle-name'),
    phoneForm = $('#phone-number');

    console.log(mNameForm);
    
    phoneForm.inputmask('(999)-999-99-99');

    loginForm.blur(function () {  
        loginForm.siblings().remove('.invalid-feedback');
        loginForm.siblings().remove('.valid-feedback');
        if (loginForm.hasClass('is-invalid')) {
            loginForm.removeClass('is-invalid');
        } else if (loginForm.hasClass('is-valid')){
            loginForm.removeClass('is-valid');
        }
        var login = loginForm.val();
        ValidateLogin(login);
    })

    passForm.blur(function () {  
        passForm.siblings().remove('.invalid-feedback');
        passForm.siblings().remove('.valid-feedback');
        if (passForm.hasClass('is-invalid')) {
            passForm.removeClass('is-invalid');
        } else if (passForm.hasClass('is-valid')){
            passForm.removeClass('is-valid');
        }
        var pass = passForm.val();
        ValidatePassword(pass);
    })

    confirmForm.blur(function () {  
        confirmForm.siblings().remove('.invalid-feedback');
        confirmForm.siblings().remove('.valid-feedback');
        if (confirmForm.hasClass('is-invalid')) {
            confirmForm.removeClass('is-invalid');
        } else if (confirmForm.hasClass('is-valid')){
            confirmForm.removeClass('is-valid');
        }
        var confirmPass = confirmForm.val();
        ValidateConfirm(confirmPass);
    })

    phoneForm.blur(function () {  
        phoneForm.siblings().remove('.invalid-feedback');
        phoneForm.siblings().remove('.valid-feedback');
        if (phoneForm.hasClass('is-invalid')) {
            phoneForm.removeClass('is-invalid');
        } else if (phoneForm.hasClass('is-valid')){
            phoneForm.removeClass('is-valid');
        }
        var phoneNumber = phoneForm.val();
        ValidatePhone(phoneNumber);
    })

    lNameForm.blur(function () {  
        lNameForm.siblings().remove('.invalid-feedback');
        lNameForm.siblings().remove('.valid-feedback');
        if (lNameForm.hasClass('is-invalid')) {
            lNameForm.removeClass('is-invalid');
        } else if (lNameForm.hasClass('is-valid')){
            lNameForm.removeClass('is-valid');
        }
        var lastName = lNameForm.val();
        ValidateLName(lastName);
    })

    fNameForm.blur(function () {  
        fNameForm.siblings().remove('.invalid-feedback');
        fNameForm.siblings().remove('.valid-feedback');
        if (fNameForm.hasClass('is-invalid')) {
            fNameForm.removeClass('is-invalid');
        } else if (fNameForm.hasClass('is-valid')){
            fNameForm.removeClass('is-valid');
        }
        var firstName = fNameForm.val();
        ValidateFName(firstName);
    })

    $('#user-middle-name').blur(function () {  
        mNameForm.siblings().remove('.invalid-feedback');
        mNameForm.siblings().remove('.valid-feedback');
        if (mNameForm.hasClass('is-invalid')) {
            mNameForm.removeClass('is-invalid');
        } else if (mNameForm.hasClass('is-valid')){
            mNameForm.removeClass('is-valid');
        }
        var middleName = mNameForm.val();
        ValidateMName(middleName);
    })
    

    $('#btn-registration').click(function () {  
    $('.valid-feedback').remove();
    var login = loginForm.val(),
            pass = passForm.val(),
            confirmPass = confirmForm.val(),
            lastName = lNameForm.val(),
            firstName = fNameForm.val(),
            middleName = mNameForm.val(),
            phoneNumber = phoneForm.val();
            
            if (ValidateLogin(login) && ValidatePassword(pass) && ValidateConfirm(confirmPass) &&
                ValidatePhone(phoneNumber) && ValidateLName(lastName) && ValidateFName(firstName) &&
                ValidateMName(middleName)) {
                user = new User(login, pass, lastName, firstName, middleName, phoneNumber);
                user = JSON.stringify(user);
                $.post('index/registration.php', {user: user}, function (response) { 
                    if (response.length != 0) {
                        alert(response);
                    } else {
                        window.location.reload();                
                    }
                })
                function User(login, pass, lastName, firstName, middleName, phoneNumber) { 
                    this.login = login,
                    this.pass = pass,
                    this.lastName = lastName,
                    this.firstName = firstName,
                    this.middleName = middleName,
                    this.phoneNumber = phoneNumber
                }
            }
        
        })


        function ValidateLogin(login) {  
            try {
                if (login !== undefined && login !== null && login.length !== 0) {
                    if (login.length >= 6 && login.length <= 24) {
                        if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(login) !== null) {
                            if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(login)[0] === login) {
                                loginForm.addClass('is-valid');
                                loginForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong Login Data');
                            }
                        } else {
                            throw new Error('Wrong Login Data');
                        }
                    } else {
                        throw new Error('Length Login Data');
                    }
                } else {
                    throw new Error('Empty Login Data');
                }
            } catch (error) {
                loginForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty Login Data') {
                    loginForm.addClass('is-invalid');
                    loginForm.after('<div class="invalid-feedback">Вы не ввели логин!</div>');
                }
                if (error.message === 'Length Login Data') {
                    loginForm.addClass('is-invalid');
                    loginForm.after('<div class="invalid-feedback">Длина логина должна быть от 6 до 24 символов!</div>');
                }
                if (error.message === 'Wrong Login Data') {
                    loginForm.addClass('is-invalid');
                    loginForm.after('<div class="invalid-feedback">Логин должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
                }
            }
        }

        function ValidatePassword(pass) {  
            try {
                if (pass !== undefined && pass !== null && pass.length !== 0) {
                    if (pass.length >= 6 && pass.length <= 24) {
                        if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(pass) !== null) {
                            if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(pass)[0] === pass) {
                                passForm.addClass('is-valid');
                                passForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong Password Data');
                            }
                        } else {
                            throw new Error('Wrong Password Data');
                        }
                    } else {
                        throw new Error('Length Password Data');
                    }
                } else {
                    throw new Error('Empty Password Data');
                }
            } catch (error) {
                passForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty Password Data') {
                    passForm.addClass('is-invalid');
                    passForm.after('<div class="invalid-feedback">Вы не ввели пароль!</div>');
                }
                if (error.message === 'Length Password Data') {
                    passForm.addClass('is-invalid');
                    passForm.after('<div class="invalid-feedback">Длина пароля должна быть от 6 до 24 символов!</div>');
                }
                if (error.message === 'Wrong Password Data') {
                    passForm.addClass('is-invalid');
                    passForm.after('<div class="invalid-feedback">Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
                }
            }
        }

        function ValidatePhone(phoneNumber) {  
            try {
                if (phoneNumber !== undefined && phoneNumber !== null && phoneNumber.length !== 0) {
                    if (phoneNumber.length == 15) {
                        if (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(phoneNumber) !== null) {
                            if (/[(][9][0-9]{2}[)][-][0-9]{3}[-][0-9]{2}[-][0-9]{2}/.exec(phoneNumber)[0] === phoneNumber) {
                                phoneForm.addClass('is-valid');
                                phoneForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong Phone Data');
                            }
                        } else {
                            throw new Error('Wrong Phone Data');
                        }
                    } else {
                        throw new Error('Length Phone Data');
                    }
                } else {
                    throw new Error('Empty Phone Data');
                }
            } catch (error) {
                phoneForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty Phone Data') {
                    phoneForm.addClass('is-invalid');
                    phoneForm.after('<div class="invalid-feedback">Вы не ввели номер телефона!</div>');
                }
                if (error.message === 'Length Phone Data') {
                    phoneForm.addClass('is-invalid');
                    phoneForm.after('<div class="invalid-feedback">Наш сервис работает только с операторами РФ!</div>');
                }
                if (error.message === 'Wrong Phone Data') {
                    phoneForm.addClass('is-invalid');
                    phoneForm.after('<div class="invalid-feedback">Наш сервис работает только с операторами РФ!</div>');
                }
            }
        }

        function ValidateLName(lastName) {  
            try {
                if (lastName !== undefined && lastName !== null && lastName.length !== 0) {
                    if (lastName.length >= 3 && lastName.length <= 30) {
                        if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(lastName) !== null) {
                            if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(lastName)[0] === lastName) {
                                lNameForm.addClass('is-valid');
                                lNameForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong LName Data');
                            }
                        } else {
                            throw new Error('Wrong LName Data');
                        }
                    } else {
                        throw new Error('Length LName Data');
                    }
                } else {
                    throw new Error('Empty LName Data');
                }
            } catch (error) {
                lNameForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty LName Data') {
                    lNameForm.addClass('is-invalid');
                    lNameForm.after('<div class="invalid-feedback">Вы не ввели фамилию!</div>');
                }
                if (error.message === 'Length LName Data') {
                    lNameForm.addClass('is-invalid');
                    lNameForm.after('<div class="invalid-feedback">Длина фамилии должна быть от 3 до 30 символов!</div>');
                }
                if (error.message === 'Wrong LName Data') {
                    lNameForm.addClass('is-invalid');
                    lNameForm.after('<div class="invalid-feedback">Фамилия должна состоять из латинских букв или кириллистических букв!</div>');
                }
            }
        }

        function ValidateFName(firstName) {  
            try {
                if (firstName !== undefined && firstName !== null && firstName.length !== 0) {
                    if (firstName.length >= 4 && firstName.length <= 15) {
                        if (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(firstName) !== null) {
                            if (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(firstName)[0] === firstName) {
                                fNameForm.addClass('is-valid');
                                fNameForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong FName Data');
                            }
                        } else {
                            throw new Error('Wrong FName Data');
                        }
                    } else {
                        throw new Error('Length FName Data');
                    }
                } else {
                    throw new Error('Empty FName Data');
                }
            } catch (error) {
                fNameForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty FName Data') {
                    fNameForm.addClass('is-invalid');
                    fNameForm.after('<div class="invalid-feedback">Вы не ввели имя!</div>');
                }
                if (error.message === 'Length FName Data') {
                    fNameForm.addClass('is-invalid');
                    fNameForm.after('<div class="invalid-feedback">Длина имени должна быть от 4 до 15 символов!</div>');
                }
                if (error.message === 'Wrong FName Data') {
                    fNameForm.addClass('is-invalid');
                    fNameForm.after('<div class="invalid-feedback">Имя должно состоять из латинских букв или кириллистических букв!</div>');
                }
            }
        }

        function ValidateMName(middleName) {  
            try {
                if (middleName !== undefined && middleName !== null && middleName.length !== 0) {
                    if (middleName.length >= 6 && middleName.length <= 24) {
                        if (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(middleName) !== null) {
                            if (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(middleName)[0] === middleName) {
                                mNameForm.addClass('is-valid');
                                mNameForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                                return true;
                            } else {
                                throw new Error('Wrong MName Data');
                            }
                        } else {
                            throw new Error('Wrong MName Data');
                        }
                    } else {
                        throw new Error('Length MName Data');
                    }
                } else {
                    throw new Error('Empty MName Data');
                }
            } catch (error) {
                mNameForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty MName Data') {
                    mNameForm.addClass('is-invalid');
                    mNameForm.after('<div class="invalid-feedback">Вы не ввели отчество!</div>');
                }
                if (error.message === 'Length MName Data') {
                    mNameForm.addClass('is-invalid');
                    mNameForm.after('<div class="invalid-feedback">Длина имени должна быть от 6 до 24 символов!</div>');
                }
                if (error.message === 'Wrong MName Data') {
                    mNameForm.addClass('is-invalid');
                    mNameForm.after('<div class="invalid-feedback">Отчество должно состоять из латинских букв или кириллистических букв!</div>');
                }
            }
        }

        function ValidateConfirm(confirmPass) {
            try {
                if (confirmPass !== undefined && confirmPass !== null && confirmPass.length !== 0) {
                    if (passForm.val() === confirmPass) {
                        confirmForm.addClass('is-valid');
                        confirmForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                        return true;
                    } else {
                        throw new Error('Uncomparable Passwords Error');
                    }
                } else {
                    throw new Error('Empty Confirm Error');
                }
            } catch (error) {
                if (error.message === 'Empty Confirm Error') {
                    confirmForm.addClass('is-invalid');
                    confirmForm.after('<div class="invalid-feedback">Вы не подтвердили пароль!</div>');
                }

                if (error.message === 'Uncomparable Passwords Error') {
                    confirmForm.addClass('is-invalid');
                    confirmForm.after('<div class="invalid-feedback">Пароли не совпадают!</div>');
                }
            }
        }    
 })