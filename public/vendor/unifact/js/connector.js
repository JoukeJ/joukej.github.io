$(document).ready(function () {
    $('.input-daterange input').each(function () {
        $(this).datepicker({
            autoclose: true,
            orientation: 'auto',
            format: 'yyyy-mm-dd',
        });
    });
});
