$(document).ready(function () {  
    var authLoginForm = $('#auth-login'),
        authPassForm = $('#auth-pass');

    authLoginForm.blur(function () {
        authLoginForm.siblings().remove('.invalid-feedback');
        authLoginForm.siblings().remove('.valid-feedback');
        if (authLoginForm.hasClass('is-invalid')) {
            authLoginForm.removeClass('is-invalid');  
        } else if (authLoginForm.hasClass('is-valid')) {
            authLoginForm.removeClass('is-valid');    
        } 
        var authLogin = authLoginForm.val();
        ValidateLogin(authLogin);
    })

    authPassForm.blur(function () {
        authPassForm.siblings().remove('.invalid-feedback');
        authPassForm.siblings().remove('.valid-feedback');
        if (authPassForm.hasClass('is-invalid')) {
            authPassForm.removeClass('is-invalid'); 
        } else if (authPassForm.hasClass('is-valid')) {
            authPassForm.removeClass('is-valid');  
        }
        var authPass = authPassForm.val();
        ValidatePass(authPass);
    })

    $('#btn-login').click(function () { 
        $('.valid-feedback') .remove();
        var authLogin = authLoginForm.val(),
            authPass = authPassForm.val();
            if (ValidateLogin(authLogin) && ValidatePass(authPass)) {
                authUser = new User(authLogin, authPass);
                authUser = JSON.stringify(authUser);
                $.post('index/login.php', {user: authUser}, function (response) {  
                    if (response.length != 0) {
                        if (authLoginForm.hasClass('is-valid') && authPassForm.hasClass('is-valid')) {
                            $('.valid-feedback').remove();
                            authLoginForm.toggleClass('is-invalid');
                            authPassForm.toggleClass('is-invalid');
                        }                        
                        authPassForm.after('<div class="invalid-feedback">'+response+'</div>');
                    } else {
                        window.location.reload();
                    }
                })
                function User(login, password) {
                    this.login = login,
                    this.password = password
                }
            }
            
    })

    function ValidateLogin(authLogin) {
        try {
            if (authLogin !== null && authLogin !== undefined && authLogin.length != 0) {
                if (authLogin.length >= 6 && authLogin.length <= 24) {
                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authLogin) !== null) {
                        if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authLogin)[0] === authLogin) {
                            authLoginForm.addClass('is-valid');
                            authLoginForm.after('<div class="valid-feedback">Корректно введённые данные!</div>');
                            return true;
                        } else {
                        throw new Error('Wrong Login Error') ;
                        }
                    } else {
                        throw new Error('Wrong Login Error');
                    }
                } else {
                    throw new Error('Length Login Error');
                }
            } else {
                throw new Error('Empty Login Error');
            }
        } catch (error) {
            authLoginForm.siblings().remove('.invalid-feedback');
            if (error.message === 'Empty Login Error') {
                authLoginForm.addClass('is-invalid');
                authLoginForm.after('<div class="invalid-feedback">Вы не ввели логин!</div>');
            }

            if (error.message === 'Length Login Error') {
                authLoginForm.addClass('is-invalid');
                authLoginForm.after('<div class="invalid-feedback">Длина логина должна быть от 6 до 24 символов!</div>');
            }

            if (error.message === 'Wrong Login Error') {
                authLoginForm.addClass('is-invalid');
                authLoginForm.after('<div class="invalid-feedback">Логин должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
            }
        }
    }

    function ValidatePass(authPass) {
        try {
            if (authPass !== null && authPass !== undefined && authPass.length != 0) {
                if (authPass.length >= 6 && authPass.length <= 24) {
                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authPass) !== null) {
                        if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authPass)[0] === authPass) {
                            authPassForm.addClass('is-valid');
                            authPassForm.after('<div class="valid-feedback">Корректно введённые данные</div>');
                            return true;
                        } else {
                        throw new Error('Wrong Pass Error') ;
                        }
                    } else {
                        throw new Error('Wrong Pass Error');
                    }
                } else {
                    throw new Error('Length Pass Error');
                }
            } else {
                throw new Error('Empty Pass Error');
            }
        } catch (error) {
            authPassForm.siblings().remove('.invalid-feedback');
            if (error.message === 'Empty Pass Error') {
                authPassForm.addClass('is-invalid');
                authPassForm.after('<div class="invalid-feedback">Вы не ввели пароль!</div>');
            }

            if (error.message === 'Length Pass Error') {
                authPassForm.addClass('is-invalid');
                authPassForm.after('<div class="invalid-feedback">Длина пароля должна быть от 6 до 24 символов!</div>');
            }

            if (error.message === 'Wrong Pass Error') {
                authPassForm.addClass('is-invalid');
                authPassForm.after('<div class="invalid-feedback">Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
            }
        }
    }
})