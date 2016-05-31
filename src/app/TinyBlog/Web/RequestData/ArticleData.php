<?php

namespace TinyBlog\Web\RequestData;

use Yen\Util\Extractor;
use Yen\Http\Contract\IServerRequest;
use DateTimeInterface;

class ArticleData
{
    protected $article_id;
    protected $title;
    protected $body;

    public function __construct($article_id, $title, $body)
    {
        $this->article_id = $article_id;
        $this->title = $title;
        $this->body = $body;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $data = $request->getParsedBody();

        $article_id = Extractor::extractInt($data, 'article_id');
        $title = Extractor::extractString($data, 'title');
        $body = Extractor::extractString($data, 'body');

        return new self($article_id, $title, $body);
    }

    public function getArticleId()
    {
        return $this->article_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }
}
