var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit'),
    btnFind = $('#btn-find-order');

$('.create-order-container').css({'display': 'none', 'margin-top': '20px'});

$('#btn-open-create-order-container').click(function () { 
    $('.create-order-container').slideToggle();
 })



btnSubmit.click(function () { 
    var user = $('#user').val(),
        product_id = $('#car').val(),
        hours = $('#hours').val(),
        order = new Order(user, product_id, hours);
        if (CheckData(order)) {
            order = JSON.stringify(order);
            $.post('orders.php', {order: order}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();                
                }
            })
        }
        

    function Order(user, product_id, hours) { 
        this.user = user,
        this.product_id = product_id,
        this.hours = hours
     }

     function CheckData(order) {
         try {
             if (order.hours !== null & order.hours !== undefined && order.hours.length != 0) {
                 if (order.hours.length <= 2) {
                     if (!isNaN(order.hours)) {
                         return true;
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
                 alert('Вы не указали количество часов!');
             }
             if (error.message === 'Length Data Error') {
                 alert('Количество часов не может превышать 72х часов!');
             }
             if (error.message === 'Wrong Data Error') {
                 alert('Поле количество часов должно состоять из цифр!');
             }
         }
     }
 })

btnFind.click(function () {  
    var order = $('#order').val();
    $.get('orders.php', {order: order}, function (response) {  
        if (response.length != 0) {
            window.location.href = 'orders.php?order=' + order;
        }
    })
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                order_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('orders.php', {id: order_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })            
        }
        
    }
})
