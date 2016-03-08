<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;
use TinyBlog\Web\RequestData\ArticleViewData;

class ViewHandler extends BaseHandler
{
    public function onGet(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->domain_registry->getArticleFinder();

        try {
            $article = $finder->getArticle($data->getArticleId());
        } catch (\InvalidArgumentException $ex) {
            return $this->notFound('page not found');
        };

        return $this->ok('Page/Article/View', $article);
    }
}
