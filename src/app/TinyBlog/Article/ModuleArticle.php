<?php

namespace TinyBlog\Article;

use Yada\Driver as SqlDriver;
use TinyBlog\Article\DataAccess\ArticleFetcher;
use TinyBlog\Article\DataAccess\ArticleStore;

class ModuleArticle
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getArticleRepo()
    {
        return new ArticleRepo($this->getArticleStore(), $this->getArticleFetcher());
    }

    private function getArticleStore()
    {
        return new ArticleStore($this->sql_driver);
    }

    private function getArticleFetcher()
    {
        return new ArticleFetcher($this->sql_driver);
    }
}
