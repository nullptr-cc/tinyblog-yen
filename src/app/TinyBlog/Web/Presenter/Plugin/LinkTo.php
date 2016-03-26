<?php

namespace TinyBlog\Web\Presenter\Plugin;

use Yen\Http\Uri;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Type\Article;

class LinkTo
{
    protected $url_builder;

    public function __construct(WebRegistry $web)
    {
        $this->url_builder = $web->getUrlBuilder();
    }

    public function __invoke()
    {
        return $this;
    }

    public function main()
    {
        return $this->url_builder->buildMainPageUrl();
    }

    public function article(Article $article)
    {
        return $this->url_builder->buildArticleUrl($article);
    }

    public function edit(Article $article)
    {
        return $this->url_builder->build(
            Uri::createFromString('/article/edit'),
            ['article_id' => $article->id()]
        );
    }
}
