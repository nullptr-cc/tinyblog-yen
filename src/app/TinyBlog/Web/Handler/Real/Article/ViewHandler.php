<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Web\RequestData\ArticleViewData;

class ViewHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $afinder = $this->modules->article()->getArticleRepo();
        $cfinder = $this->modules->comment()->getCommentRepo();

        if (!$afinder->articleExists($data->getArticleId())) {
            return $this->notFound();
        };

        $article = $afinder->getArticleById($data->getArticleId());
        $comments = $cfinder->getArticleComments($article);

        return $this->ok(
            'Page/Article/View',
            [
                'article' => $article,
                'comments' => $comments
            ]
        );
    }
}
