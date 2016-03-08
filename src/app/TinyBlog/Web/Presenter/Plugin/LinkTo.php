<?php

namespace TinyBlog\Web\Presenter\Plugin;

use Yen\Http\Uri;
use TinyBlog\Core\Contract\IDependencyContainer;
use TinyBlog\Type\IArticle;

class LinkTo
{
    public function __construct(IDependencyContainer $dc)
    {
        $this->url_builder = $dc->getTools()->getUrlBuilder();
    }

    public function __invoke()
    {
        return $this;
    }

    public function main()
    {
        return $this->url_builder->buildMainPageUrl();
    }

    public function article(IArticle $article)
    {
        return $this->url_builder->buildArticleUrl($article);
    }

    public function edit_article(IArticle $article)
    {
        return $this->url_builder->build(
            Uri::createFromString('/article/edit'),
            ['article_id' => $article->id()]
        );
    }
}
