var btnEdit = $('.btn-warning'),
    btnDelete = $('.btn-danger'),
    btnSubmit = $('#btnSubmit');

$('.create-body-container').css('display', 'none');

$('#btn-open-create-body-container').click(function () { 
    $('.create-body-container').slideToggle();
 })



btnSubmit.click(function () { 
    var title = $('#title').val(),
        oil = $('#oil').val(),
        transmission = $('#transmission').val(),
        control = $('#control').val(),
        carBody = new CarBody(title, oil, transmission, control);
        carBody = JSON.stringify(carBody);
        $.post('bodies.php', {car_body: carBody}, function (response) { 
            if (response.length != 0) {
                alert(response);
            } else {
                window.location.reload();                
            }
         })
    

    function CarBody(type, oil, transmission, control) { 
        this.type = type,
        this.oil = oil,
        this.transmission = transmission,
        this.control = control

     }
 })

btnEdit.click(function () {
    for (var i = 0; i < btnEdit.length; i++) {
        if (btnEdit[i] == event.target) {
            var position = i + 1;
            car_body_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            window.location.href = "Edit/BodiesInfo.php?body=" + car_body_id;
        }
        
    }
})

btnDelete.click(function () {  
    for (var i = 0; i < btnDelete.length; i++) {
        if (event.target == btnDelete[i]) {
            var position = i + 1,
                car_body_id = $('table tr:nth-child(' + position + ') td:first-child').text();            
            
            $.post('bodies.php', {id: car_body_id}, function (response) { 
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();     
                }
                
            })
            /*$.ajax({
                type:'delete',
                url:'options.php',
                data: option_id,
                success: function (response) { 
                    if (response.length !=0) {
                        alert(response);
                    } else {
                      window.location.reload();    
                    } 
                }
            })*/
        }
        
    }
})
