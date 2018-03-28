var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit'),
    btnFind = $('#btn-find-model');

$('.create-model-container').css('display', 'none');

$('#btn-open-create-model-container').click(function () { 
    $('.create-model-container').slideToggle();
 })

btnSubmit.click(function () {  
    var title = $('#title').val();
    if (CheckData(title)) {
        $.post('models.php', {model:title}, function (response) { 
            if (response.length != 0) {
                alert(response);
            } else {
                window.location.reload();
            }
        })
    }
    
     function CheckData(model) {
        try {
            if (model !== '' && model !== null && model !== undefined) {
                if (model.length >= 3 && model.length <= 25) {
                    return true;
                } else {
                    throw new Error('Length Data Error');
                }
            } else {
                throw new Error('Empty Data Error');
            }
        } catch (error) {
            if (error.message === 'Empty Data Error') {
                alert('Вы не ввели название опции автомобиля!');
                return false;
            }
            if (error.message === 'Length Data Error') {
                alert('Название опции автомобиля должно быть не менее 3 и не более 25 символов!');
                return false;
            }
        }
    }

})
btnEdit.click(function () {
    for (var i = 0; i < btnEdit.length; i++) {
        if (btnEdit[i] == event.target) {
            var position = i + 1;
            model_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            window.location.href = "Edit/ModelInfo.php?model=" + model_id;
        }
        
    }
})

btnFind.click(function () {  
    var model = $('#model').val();
    $.get('models.php', {model: model}, function (response) {  
        if (response.length != 0) {
            window.location.href = 'models.php?model=' + model;
        }
    })
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                model_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('models.php', {id: model_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })            
        }
        
    }
})