<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Web\RequestData\ArticleViewData;
use TinyBlog\Domain\Exception\ArticleNotFound;

class ViewHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->domain->getArticleFinder();

        try {
            $article = $finder->getArticleById($data->getArticleId());
        } catch (ArticleNotFound $ex) {
            return $this->notFound();
        };

        return $this->ok(
            'Page/Article/View',
            ['article' => $article]
        );
    }
}
