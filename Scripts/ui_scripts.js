$(document).ready(function () {
    $('#navbar li a').click(function () {
    $('#main').empty().append("<div id='loading' class='offset-md-5'><img src='styles/ajax_loader.gif'></div>");
    $('#navbar li').removeClass('current');
    $(this).parent().addClass('current');

    $.ajax({url: this.href, success: function(html) {
        $('#main').empty().append(html);
    }})
    return false;
    })
});