<?php

namespace TinyBlog\Web\Service;

use Yen\Http\Uri;
use TinyBlog\Article\Article;

class UrlBuilder extends \Yen\Util\UrlBuilder
{
    public function buildArticleUrl(Article $article)
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
