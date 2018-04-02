var btnDelete = $('.btn-danger');

btnDelete.click(function () {
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1;
            var order_id = $('tbody tr:nth-child('+position+') td:first-child').text();
            $.post('cabinet.php', {order_id: order_id}, function (response) {
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();
                }
            })
        }
        
    }
})