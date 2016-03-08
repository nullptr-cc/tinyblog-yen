$(function () {

    var elems = $('#auth_form').find('input, button');
    var button = $('#auth_form button[type="submit"]');

    $('#auth_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function () {
            elems.attr('disabled', true);
            button.html(button.data('wait'));
            return true;
        },
        success : function (resp) {
            window.location.href = resp.redirect_url;
        },
        error : function (resp) {
            UIkit.notify(resp.responseJSON.msg, {status:'danger'});
            elems.attr('disabled', false);
            button.html(button.data('lbl'));
        }
    });

});
