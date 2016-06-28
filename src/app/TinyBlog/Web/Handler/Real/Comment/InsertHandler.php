<?php

namespace TinyBlog\Web\Handler\Real\Comment;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\Handler\Exception\InvalidData;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\User\User;
use TinyBlog\Article\Exception\ArticleNotExists;
use TinyBlog\Comment\Comment;

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
        try {
            $data = $this->takeAndValidateData($request);
            $comment = $this->saveComment($data);
        } catch (InvalidData $ex) {
            return $this->getResponder()->badParams($ex->getErrors());
        } catch (ArticleNotExists $ex) {
            return $this->getResponder()->badParams(['article_id' => 'Invalid article id']);
        };

        $html = $this->renderComment($comment);
        return $this->getResponder()->ok(['comment_html' => $html]);
    }

    private function takeAndValidateData(IServerRequest $request)
    {
        $data = CommentData::createFromRequest($request);
        $validator = $this->modules->web()->getCommentDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            throw new InvalidData($vr->getErrors());
        };

        return $data;
    }

    private function saveComment(CommentData $data)
    {
        $article_repo = $this->modules->article()->getArticleRepo();
        $comment_editor = $this->modules->web()->getCommentEditor();
        $chrono = $this->modules->tools()->getChrono();

        $article = $article_repo->getArticleById($data->getArticleId());
        $comment = $comment_editor->createComment($data, $article, $this->getAuthUser(), $chrono->now());

        return $comment;
    }

    private function renderComment(Comment $comment)
    {
        $renderer = $this->modules->web()->getHtmlRenderer();
        $doc = $renderer->render('component/article/_comment', ['comment' => $comment]);

        return $doc->content();
    }
}
