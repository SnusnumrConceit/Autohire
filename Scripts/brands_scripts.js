var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit');

$('.create-brand-container').css('display', 'none');
$('#btn-open-create-brand-container').click(function () {  
    $('.create-brand-container').slideToggle();
})
btnSubmit.click(function () {  
    var title = $('#title').val();
    if (CheckData(title)) {
        $.post('brands.php', {brand: title}, function (response) {  
            if (response.length != 0) {
                alert(response);
            } else {
                window.location.reload();
            }
        })
    }

    function CheckData(brand) {
        try {
            if (brand !== '' && brand !== null && brand !== undefined) {
                if (brand.length >= 3 && brand.length <= 25) {
                    return true;
                } else {
                    throw new Error('Length Data Error');
                }
            } else {
                throw new Error('Empty Data Error');
            }
        } catch (error) {
            if (error.message === 'Empty Data Error') {
                alert('Вы не ввели название марки автомобиля!');
                return false;
            }
            if (error.message === 'Length Data Error') {
                alert('Название автомобиля должно быть не менее 3 и не более 25 символов!');
                return false;
            }
        }
    }
})

btnEdit.click(function () {
    for (var i = 0; i < btnEdit.length; i++) {
        if (btnEdit[i] == event.target) {
            var position = i + 1;
            brand_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            window.location.href = "Edit/BrandInfo.php?brand=" + brand_id;
        }
        
    }
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                brand_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('brands.php', {id: brand_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })            
        }
        
    }
})