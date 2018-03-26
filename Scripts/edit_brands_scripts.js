$('#btnSubmitEdit').click(function () { 
    let id = window.location.search.split('='),
        title = $('#title').val();
    if(CheckData(title)){
        function Brand(id, title) {
            this.id = id;
            this.title = title;
        }
        brand = new Brand(id[1], title);
        brand = JSON.stringify(brand);
        $.post('BrandInfo.php', {edit_brand: brand}, function (response) { 
            if (response.trim().length != 0) {
                alert(response);     
            } else {
                window.location.href = '../brands.php';
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
                alert('Вы не ввели название марки автомобиля!');
                return false;    
            }
            if (error.message === 'Data Length Error') {
                alert('Название марки автомобиля должно состоять от 3 до 25 символов!');    
                return false;
            }
            if (error.message === 'Uncorrect Data Error') {
                alert('Название марки автомобиля должно состоять из латинских и кириллистических букв!');    
                return false;
            }
            
        }
        
    }

    
    
 })