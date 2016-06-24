<?php

namespace TinyBlog\Web\Handler\Real\Comment;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\BaseHandler;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\User\User;

class InsertHandler extends BaseHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getJsonResponder();

        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $responder->forbidden('Blocked');
        };

        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_CONSUMER) {
            return $responder->forbidden('Not authorized');
        };

        $data = CommentData::createFromRequest($request);
        $validator = $this->modules->web()->getCommentDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $responder->badParams($vr->getErrors());
        };

        $afinder = $this->modules->article()->getArticleRepo();
        $ceditor = $this->modules->web()->getCommentEditor();

        if (!$afinder->articleExists($data->getArticleId())) {
            return $responder->badParams(['article_id' => 'Invalid article id']);
        };

        $article = $afinder->getArticleById($data->getArticleId());

        try {
            $comment = $ceditor->createComment($data, $article, $auth_user, new \DateTimeImmutable());
        } catch (\Exception $ex) {
            return $responder->error('Something wrong: ' . $ex->getMessage());
        };

        $html = $this->modules->web()->getHtmlRenderer()->render('component/article/_comment', ['comment' => $comment]);
        return $responder->ok(['comment_html' => $html->content()]);
    }
}
