<?php

namespace TinyBlog\Domain;

use TinyBlog\DataAccess\DataAccessRegistry;
use TinyBlog\Tool\ToolRegistry;
use TinyBlog\Domain\Article\ArticleFinder;
use TinyBlog\Domain\Article\ArticleEditor;
use TinyBlog\Domain\Article\ArticleValidator;
use TinyBlog\Domain\User\UserFinder;

class DomainRegistry
{
    protected $dar;
    protected $tools;

    public function __construct(DataAccessRegistry $dar, ToolRegistry $tools)
    {
        $this->dar = $dar;
        $this->tools = $tools;
    }

    public function getArticleFinder()
    {
        return new ArticleFinder($this->dar);
    }

    public function getUserFinder()
    {
        return new UserFinder($this->dar);
    }

    public function getArticleEditor()
    {
        return new ArticleEditor(
            $this->dar->getArticleSaver(),
            $this->tools->getMarkdownTransformer(),
            $this->tools->getTeaserMaker()
        );
    }
}
