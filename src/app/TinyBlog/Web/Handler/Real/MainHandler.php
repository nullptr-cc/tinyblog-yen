<?php

namespace TinyBlog\Web\Handler\Real;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\RequestData\ArticleListData;
use \TinyBlog\Web\Handler\Base\BaseHandler;

class MainHandler extends BaseHandler
{
    const ARTICLE_PER_PAGE = 3;

    public function onGet(IServerRequest $request)
    {
        $data = ArticleListData::createFromRequest($request);
        $article_finder = $this->domain_registry->getArticleFinder();

        try {
            $result = $article_finder->getArticlesListRange(
                ['created_at' => 'desc'],
                $data->getPageNum(),
                self::ARTICLE_PER_PAGE
            );
        } catch (\OutOfRangeException $ex) {
            return $this->notFound('page not found');
        };

        return $this->ok(
            'Page/Main',
            [
                'articles' => $result->articles,
                'paging' => [
                    'num' => $data->getPageNum(),
                    'count' => $result->page_count
                ]
            ]
        );
    }
}
