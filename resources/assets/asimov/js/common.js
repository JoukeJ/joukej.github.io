$(document).ready(function () {
    initSwConfirm();
});

function initSwConfirm() {
    $('.sw-confirm').click(function () {

        var dis = $(this);

        var opts = {
            text: "There is no turning back from here!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, go ahead!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        };


        if (dis.attr('data-title')) {
            opts.title = dis.attr('data-title')
        }

        if (dis.attr('data-text')) {
            opts.text = dis.attr('data-text')
        }

        if (dis.attr('data-type')) {
            opts.type = dis.attr('data-type')
        }

        if (!dis.attr('data-url')) {
            console.log('no data-url specified.');
        }

        if (dis.attr('data-confirm')) {
            opts.confirmButtonText = dis.attr('data-confirm')
        }

        if (dis.attr('data-cancel')) {
            opts.cancelButtonText = dis.attr('data-cancel')
        }

        swal(opts, function () {
            $('#sw-confirm-form').find('[name=_token]').val(dis.attr('data-csrf'));

            var method = dis.attr('data-method');

            if(typeof method !== typeof undefined && method!==false && method != ''){
                $("#sw-confirm-form").append('<input type="hidden" name="_method" value="'+method+'" />');
            }

            $('#sw-confirm-form').attr('action', dis.attr('data-url')).submit();
        });
    });

    $('#sw-confirm-form').remove();
    $('body').append('<form id="sw-confirm-form" method="post"><input type="hidden" name="_token" value="" /></form>');
}
