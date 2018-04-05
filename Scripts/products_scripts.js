var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit'),
    btnFind = $('#btn-find-product');

    $('.create-product-container').css({'display': 'none', 'margin-top': '20px'});
    $('#btn-open-create-product-container').click(function () {  
        $('.create-product-container').slideToggle();
    }) 

    btnSubmit.click(function () { 
    var photo = $('#photo').prop("files")[0],
        brand = $('#brand').val(),
        model = $('#model').val(),
        body = $('#car-body').val(),
        price = $('#price').val(),
        options = [];
        $(':checkbox:checked').each(function () {  
            options.push(this.value);
        })
        product = new Product(photo, brand, model, body, price, options),
        formData = new FormData();
        
        if (CheckData(product)) {
            formData.append('photo', product.photo);
            product = JSON.stringify(product);                        
            formData.append('product', product);
            $.ajax(
                {
                    url: 'products.php',                    
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) { 
                        if (response.length != 0) {
                            alert(response);
                        } else {
                            window.location.reload();                
                        }           
                    }    
                }
            )
        }

    function Product(photo, brand, model, body, price, options) { 
        this.photo = photo,
        this.brand = brand,
        this.model = model,
        this.body = body,
        this.price = price,
        this.options = options;
     }
    function CheckData(product) {
        try {            
            if (product.photo !== null && product.photo !== undefined && product.photo !== '' &&
                product.brand !== null && product.brand !== undefined && product.brand !== '' &&
                product.model !== null && product.model !== undefined && product.model !=='' &&
                product.body !== null  && product.body !== undefined && product.body !=='' &&
                product.price !== null && product.price !== undefined && product.price !=='') {
                if (product.price.length <= 4 && product.price.length >= 3) {
                    if (!isNaN(product.price)) {
                        return true;
                    } else {
                        throw new Error("Wrong Data Error");
                    }
                } else {
                    throw new Error("Length Data Error");    
                }
            } else {
                throw new Error("Empty Data Error");
            }    
        } catch (error) {
            if (error.message === "Empty Data Error") {
                if (product.photo === null || product.photo === undefined || product.photo === '') {
                    alert("Вы не загрузили картинку!");
                }

                if (product.brand === null || product.brand === undefined || product.brand === '') {
                    alert("Пришли неверные данные о выбранной марке автомобиля!");
                }
                
                if (product.model === null || product.model === undefined || product.model === '') {
                    alert("Пришли неверные данные о выбранной модели автомобиля!");
                }
                
                if (product.body === null || product.body === undefined || product.body === '') {
                    alert("Пришли неверные данные о выбранном кузове автомобиля!");
                }
                
                if (product.price === null || product.price === undefined || product.price === '') {
                    alert("Вы не указали цену проката!");
                }
            }
            if (error.message === "Length Data Error") {
                if (product.price.length > 4 || product.price.length < 3) {
                    alert("Цена проката должна состоять от 3 до 4 цифр!");
                }
            }
            if (error.message === "Wrong Data Error") {
                if (isNaN(product.price)) {
                        alert("Цена должна состоять из цифр!");
                }
            }
            return false;
        }
        
    }
 })

//поиск по модели автомобиля
btnFind.click(function () {  
    var product = $('#product').val();
    $.get('products.php', {product: product}, function (response) {  
        if (response.length != 0) {
            window.location.href = 'products.php?product=' + product;
        }
    })
})

btnEdit.click(function () {
    for (var i = 0; i < btnEdit.length; i++) {
        if (btnEdit[i] == event.target) {
            var position = i + 1;
            product_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            window.location.href = "Edit/Productinfo.php?product=" + product_id;
        }
        
    }
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                product_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('products.php', {id: product_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })
        }
        
    }
})