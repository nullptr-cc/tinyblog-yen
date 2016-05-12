<?php

namespace TinyBlog\Web\Handler\Real\Comment;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\Article\EArticleNotFound;
use TinyBlog\User\User;

class InsertHandler extends AjaxHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_CONSUMER) {
            return $this->forbidden('Not authorized');
        };

        $data = CommentData::createFromRequest($request);
        $validator = $this->modules->web()->getCommentDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->badParams($vr->getErrors());
        }

        $afinder = $this->modules->article()->getArticleRepo();
        $ceditor = $this->modules->web()->getCommentEditor();

        try {
            $article = $afinder->getArticleById($data->getArticleId());
            $comment = $ceditor->createComment($data, $article, $auth_user, new \DateTimeImmutable());
        } catch (EArticleNotFound $ex) {
            return $this->badParams(['article_id' => 'Invalid article id']);
        } catch (\Exception $ex) {
            return $this->error('Something wrong: ' . $ex->getMessage());
        };

        $html = $this->modules->web()->getHtmlRenderer()->render('component/article/_comment', ['comment' => $comment]);
        return $this->ok(['comment_html' => $html->content()]);
    }
}
