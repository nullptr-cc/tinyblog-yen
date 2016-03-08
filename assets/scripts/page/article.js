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
