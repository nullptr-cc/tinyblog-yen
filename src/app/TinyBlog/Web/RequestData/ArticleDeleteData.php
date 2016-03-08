<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;

class ArticleDeleteData
{
    protected $article_id;

    public function __construct($article_id)
    {
        $this->article_id = $article_id;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $article_id = 0;
        if (array_key_exists('article_id', $request->getParsedBody())) {
            $article_id = intval($request->getParsedBody()['article_id']);
        };

        return new self($article_id);
    }

    public function getArticleId()
    {
        return $this->article_id;
    }
}
