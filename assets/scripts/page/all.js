$(function () {

    $('#sign_out_link').on('click', function () {
        var csrf_guard = $('#sign_out_form input[data-csrf-guard]');
        var hdrs = {};
        hdrs['x-' + csrf_guard.attr('name')] = csrf_guard.val();
        $(this).html($(this).data('lbl-wait'));
        $('#sign_out_form').ajaxSubmit({
            dataType : 'json',
            success : function (resp) {
                window.location.href = resp.redirect_url;
            },
            error : function (resp) {
                UIkit.notify(resp.responseJSON.msg, {status:'danger'});
            },
            headers : hdrs
        });
    });

});
