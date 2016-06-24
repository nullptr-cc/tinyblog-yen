<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\BaseHandler;
use TinyBlog\Web\RequestData\ArticleViewData;

class ViewHandler extends BaseHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getHtmlResponder();

        $data = ArticleViewData::createFromRequest($request);
        $article_repo = $this->modules->article()->getArticleRepo();
        $comment_repo = $this->modules->comment()->getCommentRepo();

        if ($article_repo->articleNotExists($data->getArticleId())) {
            return $responder->notFound();
        };

        $article = $article_repo->getArticleById($data->getArticleId());
        $comments = $comment_repo->getArticleComments($article);

        return $responder->ok(
            'Page/Article/View',
            [
                'article' => $article,
                'comments' => $comments
            ]
        );
    }
}
