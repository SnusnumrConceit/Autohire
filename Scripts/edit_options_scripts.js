$('#btnSubmitEdit').click(function () { 
    let id = window.location.search.split('='),
        title = $('#title').val();
    if(CheckData(title)){
        function Option(id, title) {
            this.id = id;
            this.title = title;
        }
        option = new Option(id[1], title);
        option = JSON.stringify(option);
        $.post('optioninfo.php', {edit_option: option}, function (response) { 
            if (response.trim().length != 0) {
                alert(response);     
            } else {
                window.location.href = '../options.php';
            }
            
        })
    };
    function CheckData(data) {  
        try {
            let titleRegExp = /[a-zA-ZА-Яа-я]/;
            if (data !== '') {
                if (data.length >= 3 && data.length <= 25) {
                    if (titleRegExp.exec(data)) {
                        return true;
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
                alert('Вы не ввели название опции автомобиля!');
                return false;    
            }
            if (error.message === 'Data Length Error') {
                alert('Название опции автомобиля должно состоять от 3 до 25 символов!');    
                return false;
            }
            if (error.message === 'Uncorrect Data Error') {
                alert('Название опции должно состоять из латинских и кириллистических букв!');    
                return false;
            }
            
        }
        
    }

    
    
 })