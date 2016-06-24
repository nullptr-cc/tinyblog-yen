<?php

namespace TinyBlog\Web\Handler\Real;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\RequestData\ArticleListData;
use TinyBlog\Web\Handler\BaseHandler;

class MainHandler extends BaseHandler
{
    const ARTICLE_PER_PAGE = 3;

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getHtmlResponder();

        $data = ArticleListData::createFromRequest($request);
        $article_finder = $this->modules->article()->getArticleRepo();

        $result = $article_finder->getArticlesListRange(
            ['created_at' => 'desc'],
            $data->getPageNum(),
            self::ARTICLE_PER_PAGE
        );

        if ($result->page_count && !count($result->articles)) {
            return $responder->notFound();
        };


        return $responder->ok(
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
