<?php

namespace TinyBlog\Web\Handler\Real;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\RequestData\ArticleListData;
use TinyBlog\Web\Handler\Base\CommonHandler;

class MainHandler extends CommonHandler
{
    const ARTICLE_PER_PAGE = 3;

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $data = ArticleListData::createFromRequest($request);
        $article_finder = $this->modules->article()->getArticleRepo();

        $result = $article_finder->getArticlesListRange(
            ['created_at' => 'desc'],
            $data->getPageNum(),
            self::ARTICLE_PER_PAGE
        );

        if ($result->page_count && !count($result->articles)) {
            return $this->notFound();
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
