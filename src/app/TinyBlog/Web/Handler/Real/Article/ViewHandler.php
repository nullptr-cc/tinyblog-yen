<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\RequestData\ArticleViewData;

class ViewHandler extends QueryHandler
{
    protected function handleRequest(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $article_repo = $this->modules->article()->getArticleRepo();
        $comment_repo = $this->modules->comment()->getCommentRepo();

        if ($article_repo->articleNotExists($data->getArticleId())) {
            return $this->getResponder()->notFound();
        };

        $article = $article_repo->getArticleById($data->getArticleId());
        $comments = $comment_repo->getArticleComments($article);

        return $this->getResponder()->ok(
            'Page/Article/View',
            [
                'article' => $article,
                'comments' => $comments
            ]
        );
    }
}
