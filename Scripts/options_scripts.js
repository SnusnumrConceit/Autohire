var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit'),
    btnFind = $('#btn-find-option');

$('.create-option-container').css('display', 'none');

$('#btn-open-create-option-container').click(function () { 
    $('.create-option-container').slideToggle();
 })

btnSubmit.click(function () {  
    var title = $('#title').val();
    if (CheckData(title)) {
        $.post('options.php', {option: title}, function (response) {  
            if (response.length != 0) {
                alert(response);
            } else {
                window.location.reload();
            }
        })
    }

    function CheckData(option) {
        try {
            if (option !== '' && option !== null && option !== undefined) {
                if (option.length >= 3 && option.length <= 25) {
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
            option_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            window.location.href = "Edit/OptionInfo.php?option=" + option_id;
        }
        
    }
})

btnFind.click(function () {  
    var option = $('#option').val();
    $.get('options.php', {option: option}, function (response) {  
        if (response.length != 0) {
            window.location.href = 'options.php?option=' + option;
        }
    })
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                option_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('options.php', {id: option_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })
            
        }
        
    }
})