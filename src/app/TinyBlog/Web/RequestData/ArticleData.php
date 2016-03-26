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
    protected $created_at;

    public function __construct($article_id = 0, $title = '', $body = '', $created_at = null)
    {
        $this->article_id = $article_id;
        $this->title = $title;
        $this->body = $body;
        $this->created_at = $created_at;
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

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function withCreatedAt(DateTimeInterface $created_at)
    {
        return new self(
            $this->article_id,
            $this->title,
            $this->body,
            $created_at
        );
    }

    public function dump()
    {
        return [
            'article_id' => $this->article_id,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at
        ];
    }
}
