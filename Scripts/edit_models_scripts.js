$('#btnSubmitEdit').click(function () { 
    let id = window.location.search.split('='),
        title = $('#title').val();
    if (CheckData(title)){
        function Model(id, title) {
            this.id = id;
            this.title = title;
        }
        model = new Model(id[1], title);
        model = JSON.stringify(model);
        $.post('modelinfo.php', {edit_model: model}, function (response) { 
            if (response.trim().length != 0) {
                alert(response);     
            } else {
                window.location.href = '../Models.php';
            }
            
        })
    };
    function CheckData(data) {  
        try {
            if (data !== '' && data !== null && data !== undefined) {
                if (data.length >= 3 && data.length <= 25) {
                    if (/[a-zA-Zа-яА-ЯёЁ]+/.exec(data) !== null) {
                        if (/[a-zA-Zа-яА-ЯёЁ]+/.exec(data)[0] == data) {
                            return true;
                        } else {
                            throw new Error('Uncorrect Data Error');
                        }
                    } else {
                        throw new Error('Uncorrect Data Error');
                    }    
                } else {
                    throw new Error('Data Length Error');
                }                
            } else {
                throw new Error('Empty Data Error');
            }
            
        } catch (error) {
            if (error.message === 'Empty Data Error') {
                alert('Вы не ввели название модели!');
                return false;    
            }
            if (error.message === 'Data Length Error') {
                alert('Название модели должно состоять от 3 до 25 символов!');    
                return false;
            }
            if (error.message === 'Uncorrect Data Error') {
                alert('Название модели должно состоять из латинских и кириллистических букв!');    
                return false;
            }
            
        }
        
    }

    
    
 })