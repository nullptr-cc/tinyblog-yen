<?php

namespace TinyBlog\Web\Handler\Real;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\RequestData\ArticleListData;

class MainHandler extends QueryHandler
{
    const ARTICLE_PER_PAGE = 3;

    protected function handleRequest(IServerRequest $request)
    {
        $data = ArticleListData::createFromRequest($request);
        $article_finder = $this->modules->article()->getArticleRepo();

        $result = $article_finder->getArticlesListRange(
            ['created_at' => 'desc'],
            $data->getPageNum(),
            self::ARTICLE_PER_PAGE
        );

        if ($result->page_count && !count($result->articles)) {
            return $this->getResponder()->notFound();
        };

        return $this->getResponder()->ok(
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
