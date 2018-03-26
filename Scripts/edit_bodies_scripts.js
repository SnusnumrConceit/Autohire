$('#btnSubmitEdit').click(function () { 
    var id = window.location.search.split('=');
        title = $('#title').val(),
        oil = $('#oil').val(),
        transmission = $('#transmission').val(),
        control = $('#control').val(),
        carBody = new CarBody(id, title, oil, transmission, control);
        carBody = JSON.stringify(carBody);
        $.post('bodiesinfo.php', {edit_body: carBody}, function (response) { 
            if (response.trim().length != 0) {
                alert(response);
            } else {
                window.location.href = '../Bodies.php';                
            }
         })

        function CarBody(id, type, oil, transmission, control) { 
            this.id = id[1];
            this.type = type,
            this.oil = oil,
            this.transmission = transmission,
            this.control = control;
     }
})