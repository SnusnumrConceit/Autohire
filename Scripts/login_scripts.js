$('#btn-login').click(function () {  
    var authLogin = $('#auth-login').val(),
        authPass = $('#auth-pass').val(),
        authUser = new User(authLogin, authPass);
        if (CheckData(authUser)) {
            authUser = JSON.stringify(authUser);
            $.post('index/login.php', {user: authUser}, function (response) {  
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();
                }
            })
        }

        function User(login, password) {
            this.login = login,
            this.password = password
        }

        function CheckData(user) {
            try {
                if (user.login !== null && user.login !== undefined && user.login.length != 0 &&
                    user.password !== null && user.password !== undefined && user.password.length != 0) {
                    if (user.login.length >= 6 && user.login.length <= 24 &&
                        user.password.length >= 6 && user.password.length <= 24) {
                        if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) !== null) &&
                            (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.password) !== null)) {
                            
                            if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] === user.login) &&
                                (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.password)[0] === user.password)) {
                                    return true;
                            } else {
                                throw new Error('Wrong Error Data');
                            }

                        } else {
                            throw new Error('Wrong Error Data');
                        }
                    } else {
                        throw new Error('Length Error Data');
                    }
                } else {
                    throw new Error('Empty Error Data');
                }
            } catch (error) {
                if (error.message === 'Empty Error Data') {
                    if (user.login === null || user.login === undefined || user.login.length == 0) {
                        alert("Вы не ввели логин!");
                    }

                    if (user.password !== null || user.password !== undefined || user.password.length != 0) {
                        alert("Вы не ввели пароль!");
                    }
                }

                if (error.message === 'Length Error Data') {
                    if (user.login.length < 6 || user.login.length > 24) {
                        alert('Длина логина должна быть от 6 до 24 символов!');  
                    }

                    if (user.password.length < 6 || user.password.length > 24) {
                        alert('Длина пароля должна быть от 6 до 24 символов!');  
                    }
                }

                if (error.message === 'Wrong Error Data') {
                    if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) === null) || (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] !== user.login)) {
                        alert('Логин должен состоять из латинских букв, точки и нижнего подчёркивания!');
                    }

                    if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.password) === null || (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.password)[0] !== user.password)){
                        alert('Пароль должен состоять из латинских букв, точки и нижнего подчёркивания!');
                    }
                }
            }
        }
})