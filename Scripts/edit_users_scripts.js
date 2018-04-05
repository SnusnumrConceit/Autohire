$('#btnSubmitEdit').click(function () { 
    var id = window.location.search.split('=');
        login = $('#login').val(),        
        lastName = $('#last-name').val(),
        firstName = $('#first-name').val(),
        middleName = $('#middle-name').val(),
        user = new User(id, login, lastName, firstName, middleName);
        if (CheckData(user)) {
            user = JSON.stringify(user);
            $.post('userinfo.php', {edit_user: user}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.href = '../Users.php';
                }
            })
        }

    function User(id, login, lastName, firstName, middleName) { 
        this.id = id[1],
        this.login = login,
        this.lastName = lastName,
        this.firstName = firstName,
        this.middleName = middleName
     }

    function CheckData(user) {  
        try {
            if (user.login !== undefined && user.login !== null && user.login.length !== 0 &&                
                user.lastName !== undefined && user.lastName !== null && user.lastName.length !== 0 &&
                user.firstName !== undefined && user.firstName !== null && user.firstName.length !== 0 &&
                user.middleName !== undefined && user.middleName !== null && user.middleName.length !== 0) {
                    if (user.login.length >= 6 && user.login.length <= 24 &&
                        user.lastName.length >= 3 && user.lastName.length <= 30 &&
                        user.firstName.length >= 4 && user.firstName.length <= 15 &&
                        user.middleName.length >= 6 && user.middleName.length <= 24) {
                            if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) !== null)
                                && (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName) !== null)
                                && (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName) !== null)
                                && (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName) !== null)) {
                                    if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] === user.login)
                                        && (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName)[0] === user.lastName)
                                        && (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName)[0] === user.firstName)
                                        && (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName)[0] === user.middleName)) {
                                            console.log(/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName));
                                            console.log(/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName));
                                            console.log(/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName));
                                            return true;
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
                    alert('Вы не ввели логин!');
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
            }
            
            if (error.message === 'Length Data Error') {
                
                if (user.login.length < 6 || user.login.length > 24) {
                    alert('Длина логина должна быть от 6 до 24 символов!');
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
                if (/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login) === null){                    
                    alert('Логин должен состоять из латинских букв, точки и нижнего подчёркивания!');
                } else if ((/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/.exec(user.login)[0] !== user.login)) {
                    alert('Логин должен состоять из латинских букв, точки и нижнего подчёркивания!');
                }
                
                if ((/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName) === null)) {
                    console.log(/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName));
                    alert('Фамилия должна начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!');
                } else if (/([A-Z][a-z]{2,})|([А-Я][a-я]{2,})/.exec(user.lastName)[0] !== user.lastName) {
                        alert('Фамилия должна начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!');    
                } 
                    
                if (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName) === null) {
                    console.log(/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName));
                    alert('Имя должно начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!');
                } else if (/([A-Z][a-z]{3,})|([А-Я][a-я]{3,})/.exec(user.firstName)[0] !== user.firstName){
                    alert('Имя должно начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!');
                }

                if ((/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName) === null)) {
                    alert('Отчество должно начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!');
                } else if (/([A-Z][a-z]{5,})|([А-Я][a-я]{5,})/.exec(user.middleName)[0] !== user.middleName){
                    alert('Отчество должно начинаться с заглавной буквы и состоять из латинских букв или кириллистических букв!')
                }
                                
            }
            
        }
    }
 })