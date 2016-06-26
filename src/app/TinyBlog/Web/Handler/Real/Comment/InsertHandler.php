<?php

namespace TinyBlog\Web\Handler\Real\Comment;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\User\User;

class InsertHandler extends CommandHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() < User::ROLE_CONSUMER) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $data = CommentData::createFromRequest($request);
        $validator = $this->modules->web()->getCommentDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->getResponder()->badParams($vr->getErrors());
        };

        $afinder = $this->modules->article()->getArticleRepo();
        $ceditor = $this->modules->web()->getCommentEditor();

        if (!$afinder->articleExists($data->getArticleId())) {
            return $this->getResponder()->badParams(['article_id' => 'Invalid article id']);
        };

        $article = $afinder->getArticleById($data->getArticleId());

        try {
            $comment = $ceditor->createComment($data, $article, $this->getAuthUser(), new \DateTimeImmutable());
        } catch (\Exception $ex) {
            return $this->getResponder()->error('Something wrong: ' . $ex->getMessage());
        };

        $html = $this->modules->web()->getHtmlRenderer()->render('component/article/_comment', ['comment' => $comment]);
        return $this->getResponder()->ok(['comment_html' => $html->content()]);
    }
}
