$(function () {

    $('[data-article-delete]').on('click', function (ev) {
        var elem = $(this);
        UIkit.modal.confirm(
            "Delete this article?",
            function () {
                elem.parent().find('form').ajaxSubmit({
                    dataType : 'json',
                    data : {
                        article_id : elem.data('article-delete')
                    },
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
            }
        );
    });

    $('#article_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function (arr, frm, opts) {
            frm.find('input, button').attr('disabled', true);
            var cg = frm.find('input[data-csrf-guard]');
            opts.headers['x-' + cg.attr('name')] = cg.val();
            return true;
        },
        success : function (resp) {
            window.location.href = resp.article_url;
        },
        error : function (resp) {
            $('#article_form').find('input, button').attr('disabled', false);
            var errors = resp.responseJSON;
            for (var k in errors) {
                UIkit.notify(errors[k], {status:'danger'});
            };
        },
        headers : {}
    });

    $('#article_form button[data-cancel]').on('click', function () {
        window.history.go(-1);
    });

    $('#comment_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function (arr, frm, opts) {
            frm.find('textarea, button').attr('disabled', true);
            var cg = frm.find('input[data-csrf-guard]');
            opts.headers['x-' + cg.attr('name')] = cg.val();
            return true;
        },
        success : function (resp) {
            var frm = $('#comment_form');
            frm.find('textarea, button').attr('disabled', false);
            frm.get(0).reset();
            var itm = $(resp.comment_html).hide().fadeIn(1500);
            $('#article_comments').append(itm);
            var cc = $('#comments_count');
            cc.html(parseInt(cc.html()) + 1);
        },
        error : function (resp) {
            $('#comment_form').find('textarea, button').attr('disabled', false);
            var errors = resp.responseJSON;
            for (var k in errors) {
                UIkit.notify(errors[k], {status:'danger'});
            };
        },
        headers : {}
    });

});
