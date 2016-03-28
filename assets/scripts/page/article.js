$(function () {

    $('[data-article-delete]').on('click', function (ev) {
        var article_id = $(this).data('article-delete');
        UIkit.modal.confirm(
            "Delete this article?",
            function () {
                $.post(
                    '/article/delete',
                    {article_id : article_id},
                    function (resp) {
                        window.location.href = resp.redirect_url;
                    },
                    'json'
                ).fail(
                    function (resp) {
                        UIkit.notify(resp.responseJSON.msg, {status:'danger'});
                    }
                );
            }
        );
    });

    $('#article_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function (arr, frm, opts) {
            frm.find('input, button').attr('disabled', true);
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
        }
    });

    $('#article_form button[data-cancel]').on('click', function () {
        window.history.go(-1);
    });

    $('#comment_form').ajaxForm({
        dataType : 'json',
        beforeSubmit : function (arr, frm, opts) {
            frm.find('textarea, button').attr('disabled', true);
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
        }
    });

});

$(function () {

    var thread = $('#disqus_thread');
    if (!thread.length) {
        return;
    };

    window.disqus_config = function () {
        this.page.url = window.location.href;
        this.page.identifier = thread.data('pageid');
    };

    var d = document,
        s = d.createElement('script');
    s.src = '//' + thread.data('siteid') + '/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);

});
