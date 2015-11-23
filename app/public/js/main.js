$(document).ready(function() {
    var sum = 0;
    $('.price').each(function() {
        var price = $(this);
        sum += parseFloat(price.html());
    });

    $('#total_price').html('Total: ' + sum);
});