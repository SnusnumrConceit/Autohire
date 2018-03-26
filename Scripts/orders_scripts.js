var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit');

$('.create-order-container').css('display', 'none');

$('#btn-open-create-order-container').click(function () { 
    $('.create-order-container').slideToggle();
 })



btnSubmit.click(function () { 
    var user = $('#user').val(),
        car = $('#car').val(),
        order = new Order(user, car);
        order = JSON.stringify(order);
        $.post('orders.php', {order: order}, function (response) { 
            if (response.length != 0) {
                alert(response);
            } else {
                window.location.reload();                
            }
         })
    

    function Order(user, car) { 
        this.user = user,
        this.car = car
     }
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
