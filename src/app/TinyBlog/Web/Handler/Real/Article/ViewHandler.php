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
        $finder = $this->domain_registry->getArticleFinder();

        try {
            $article = $finder->getArticle($data->getArticleId());
        } catch (\InvalidArgumentException $ex) {
            return $this->notFound();
        };

        return $this->ok(
            'Page/Article/View',
            ['article' => $article]
        );
    }
}
