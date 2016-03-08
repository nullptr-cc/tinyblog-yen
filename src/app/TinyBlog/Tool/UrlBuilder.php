<?php

namespace TinyBlog\Tool;

use Yen\Http\Uri;
use TinyBlog\Type\IArticle;

class UrlBuilder extends \Yen\Util\UrlBuilder
{
    public function buildArticleUrl(IArticle $article)
    {
        return $this->build(
            Uri::createFromString('route:article'),
            ['article_id' => $article->getId()]
        );
    }

    public function buildMainPageUrl(array $args = [])
    {
        return $this->build($this->base_url, $args);
    }
}
