<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;
use Yen\Util\Extractor;

class CommentData
{
    protected $article_id;
    protected $body;

    public function __construct($article_id, $body)
    {
        $this->article_id = $article_id;
        $this->body = $body;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $post = $request->getParsedBody();

        return new self(
            Extractor::extractInt($post, 'article_id'),
            Extractor::extractString($post, 'body')
        );
    }

    public function getArticleId()
    {
        return $this->article_id;
    }

    public function getBody()
    {
        return $this->body;
    }
}
