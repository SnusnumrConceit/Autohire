$('#btn-rent').click(function () {  
    var hours = $('#hours').val(),
        product_id = window.location.search.split('='),
        order = new Order(product_id, hours);
        if (CheckData(order)) {
            order = JSON.stringify(order);
            $.post('car.php', {order:order}, function (response) {  
                if (response.length != 0) {
                    alert(response);
                } else {
                    alert('Ваш заказ принят! Ожидайте звонка оператора!');
                }
            })
        }


        function Order(product_id, hours) {
            this.product_id = product_id[1],
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