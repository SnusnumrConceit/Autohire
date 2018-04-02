$('#auth-login').blur(function () {
    if ($('#auth-login').hasClass('is-invalid')) {
        $('#auth-login').removeClass('is-invalid');    
        $('.invalid-feedback').remove();
    } else if ($('#auth-login').hasClass('is-valid')) {
        $('#auth-login').removeClass('is-valid');    
        $('.valid-feedback').remove();
    } 
    var authLogin = $('#auth-login').val();
    ValidateLogin(authLogin);
})

$('#auth-pass').blur(function () {
    var authPass = $('#auth-pass').val();
    if ($('#auth-pass').hasClass('is-invalid')) {
        $('#auth-pass').removeClass('is-invalid');    
        $('.invalid-feedback').remove();
    } else if ($('#auth-pass').hasClass('is-valid')) {
        $('#auth-pass').removeClass('is-valid');    
        $('.valid-feedback').remove();
    }
    ValidatePass(authPass);
})
$('#btn-login').click(function () {  
    var authLogin = $('#auth-login').val(),
        authPass = $('#auth-pass').val();
        if (ValidateLogin(authLogin) && ValidatePass(authPass)) {
            authUser = new User(authLogin, authPass);
            authUser = JSON.stringify(authUser);
            $.post('index/login.php', {user: authUser}, function (response) {  
                if (response.length != 0) {
                    alert(response);
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
                        $('#auth-login').toggleClass('is-valid disabled');
                        $('#auth-login').after('<div class="valid-feedback">Корректно введённые данные!</div>');
                        $('#auth-login').prop('disabled', true);
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
        if (error.message === 'Empty Login Error') {
            $('#auth-login').toggleClass('is-invalid');
            $('#auth-login').after('<div class="invalid-feedback">Вы не ввели логин!</div>');
        }

        if (error.message === 'Length Login Error') {
            $('#auth-login').toggleClass('is-invalid');
            $('#auth-login').after('<div class="invalid-feedback">Длина логина должна быть от 6 до 24 символов!</div>');
        }

        if (error.message === 'Wrong Login Error') {
            $('#auth-login').toggleClass('is-invalid');
            $('#auth-login').after('<div class="invalid-feedback">Логин должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
        }
    }
}

function ValidatePass(authPass) {
    try {
        if (authPass !== null && authPass !== undefined && authPass.length != 0) {
            if (authPass.length >= 6 && authPass.length <= 24) {
                if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authPass) !== null) {
                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(authPass)[0] === authPass) {
                        $('#auth-pass').toggleClass('is-valid');
                        $('#auth-pass').after('<div class="valid-feedback">Корректно введённые данные</div>');
                        $('#auth-pass').prop('disabled', true);
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
        if (error.message === 'Empty Pass Error') {
            $('#auth-pass').toggleClass('is-invalid');
            $('#auth-pass').after('<div class="invalid-feedback">Вы не ввели пароль!</div>');
        }

        if (error.message === 'Length Pass Error') {
            $('#auth-pass').toggleClass('is-invalid');
            $('#auth-pass').after('<div class="invalid-feedback">Длина пароля должна быть от 6 до 24 символов!</div>');
        }

        if (error.message === 'Wrong Pass Error') {
            $('#auth-pass').toggleClass('is-invalid');
            $('#auth-pass').after('<div class="invalid-feedback">Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!</div>');
        }
    }
}