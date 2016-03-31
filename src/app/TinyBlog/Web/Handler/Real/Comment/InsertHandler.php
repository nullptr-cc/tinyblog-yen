<?php

namespace TinyBlog\Web\Handler\Real\Comment;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\Domain\Exception\ArticleNotFound;
use TinyBlog\Type\User;

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
        $validator = $this->web->getCommentDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->badParams($vr->getErrors());
        }

        $afinder = $this->domain->getArticleFinder();
        $ceditor = $this->web->getCommentEditor();

        try {
            $article = $afinder->getArticleById($data->getArticleId());
            $comment = $ceditor->createComment($data, $article, $auth_user, new \DateTimeImmutable());
        } catch (ArticleNotFound $ex) {
            return $this->badParams(['article_id' => 'Invalid article id']);
        } catch (\Exception $ex) {
            return $this->error('Something wrong: ' . $ex->getMessage());
        };

        $html = $this->web->getHtmlRenderer()->render('component/article/_comment', ['comment' => $comment]);
        return $this->ok(['comment_html' => $html->content()]);
    }
}
