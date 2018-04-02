$('#btnSubmit').click(function () { 
    var id = window.location.search.split('='),
        photo = $('#photo').prop("files")[0],
        brand = $('#brand').val(),
        model = $('#model').val(),
        body = $('#car-body').val(),
        price = $('#price').val(),
        options = [];
        $(':checkbox:checked').each(function () {  
            options.push(this.value);
        })
        product = new Product(id, photo, brand, model, body, price, options),
        formData = new FormData();
        
        if (CheckData(product)) {
            formData.append('photo', product.photo);
            product = JSON.stringify(product);                        
            formData.append('product', product);
            $.ajax(
                {
                    url: 'productinfo.php',                    
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) { 
                        if (response.length != 0) {
                            alert(response);
                        } else {
                            window.location.href='../products.php';                
                        }           
                    }    
                }
            )
        }

    function Product(id, photo = null, brand, model, body, price, options) { 
        this.id = id[1],
        this.photo = photo,
        this.brand = brand,
        this.model = model,
        this.body = body,
        this.price = price,
        this.options = options;
     }
    function CheckData(product) {
        try {            
            if (product.brand !== null && product.brand !== undefined && product.brand !== '' &&
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