<?php

namespace TinyBlog\Domain;

use TinyBlog\Core\Contract\IDependencyContainer;

class DomainRegistry
{
    protected $dc;

    public function __construct(IDependencyContainer $dc)
    {
        $this->dc = $dc;
    }

    public function getArticleFinder()
    {
        return new Service\ArticleFinder($this->dc->getDataAccessRegistry());
    }

    public function getUserFinder()
    {
        return new Service\UserFinder($this->dc->getDataAccessRegistry());
    }

    public function getArticleEditor()
    {
        return new Service\ArticleEditor(
            $this->dc->getDataAccessRegistry()->getArticleSaver(),
            $this->dc->getTools()->getMarkdownTransformer(),
            $this->dc->getTools()->getTeaserMaker()
        );
    }
}
