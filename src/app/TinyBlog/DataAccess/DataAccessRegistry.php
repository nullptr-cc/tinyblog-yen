<?php

namespace TinyBlog\DataAccess;

use Yada\Driver;

class DataAccessRegistry
{
    protected $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getArticleSaver()
    {
        return new Article\ArticleSaver($this->sql_driver);
    }

    public function getArticleWithUserFetcher()
    {
        return new Article\ArticleWithUserFetcher($this->sql_driver);
    }

    public function getUserFetcher()
    {
        return new User\UserFetcher($this->sql_driver);
    }
}
