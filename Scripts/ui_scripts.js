
    $('#navbar li a').click(function () {
    $('#main').empty().append("<div id='loading' class='offset-md-5'><img src='styles/ajax_loader.gif'></div>");
    $('#navbar li').removeClass('current');
    $(this).parent().addClass('current');

    $.ajax({url: this.href, success: function(html) {
        $('#main').empty().append(html);
        var btnRents = $('.btn-rent');
        btnRents.click(function () {
            for (var index = 0; index < btnRents.length; index++) {
                if (btnRents[index] == event.target) {
                    var position = index + 1,
                        product_id = $('tr:nth-child('+ position+') .d-none').text();
                        var hours = $('tr:nth-child('+position+') .hours').val(),
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
                }
                
            }
        })
    }})
    
    return false;
})



    