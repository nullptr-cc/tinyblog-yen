<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;
use Yen\Util\Extractor;

class ArticleDeleteData
{
    protected $article_id;

    public function __construct($article_id)
    {
        $this->article_id = $article_id;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $article_id = Extractor::extractInt($request->getParsedBody(), 'article_id');

        return new self($article_id);
    }

    public function getArticleId()
    {
        return $this->article_id;
    }
}
