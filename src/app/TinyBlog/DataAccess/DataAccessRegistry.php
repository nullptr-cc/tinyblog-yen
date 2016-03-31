<?php

namespace TinyBlog\DataAccess;

use Yen\Settings\Contract\ISettings;
use Yada\Driver as YadaDriver;

class DataAccessRegistry
{
    protected $settings;
    protected $sql_driver;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
        $this->sql_driver = $this->makeSqlDriver();
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

    public function getUserSaver()
    {
        return new User\UserSaver($this->sql_driver);
    }

    public function getCommentWithAuthorFetcher()
    {
        return new Comment\CommentWithAuthorFetcher($this->sql_driver);
    }

    public function getCommentSaver()
    {
        return new Comment\CommentSaver($this->sql_driver);
    }

    public function getOAuthUserStore()
    {
        return new OAuthUser\OAuthUserStore($this->sql_driver);
    }

    protected function makeSqlDriver()
    {
        return new YadaDriver(
            $this->settings->get('dsn'),
            $this->settings->get('username'),
            $this->settings->get('password')
        );
    }
}
