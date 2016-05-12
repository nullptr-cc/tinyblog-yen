<?php

namespace TinyBlog\Article;

use Yada\Driver;

class ModuleArticle
{
    private $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getArticleRepo()
    {
        return new ArticleRepo($this->getArticleStore(), $this->getArticleFetcher());
    }

    private function getArticleStore()
    {
        return new DataAccess\ArticleStore($this->sql_driver);
    }

    private function getArticleFetcher()
    {
        return new DataAccess\ArticleFetcher($this->sql_driver);
    }
}
