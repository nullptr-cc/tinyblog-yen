$(function () {

    var elems = $('#auth_form').find('input, button');
    var button = $('#auth_form button[type="submit"]');

    $('#auth_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function (arr, frm, opts) {
            UIkit.notify.closeAll();
            elems.attr('disabled', true);
            button.html(button.data('wait'));
            var cg = frm.find('input[data-csrf-guard]');
            opts.headers['x-' + cg.attr('name')] = cg.val();
            return true;
        },
        success : function (resp) {
            window.location.href = resp.redirect_url;
        },
        error : function (resp) {
            UIkit.notify(resp.responseJSON.msg, {status:'danger'});
            elems.attr('disabled', false);
            button.html(button.data('lbl'));
        },
        headers : {}
    });

    $('a[data-oauth]').on('click', function () {
        $('#oauth_form').ajaxSubmit({
            dataType : 'json',
            url : $(this).data('oauth'),
            beforeSubmit : function (arr, frm, opts) {
                var cg = frm.find('input[data-csrf-guard]');
                opts.headers['x-' + cg.attr('name')] = cg.val();
                return true;
            },
            success : function (resp) {
                window.location.href = resp.redirect_url;
            },
            error : function (resp) {
                UIkit.notify(resp.responseJSON.msg, {status:'danger'});
            },
            headers : {}
        });
    });
});
