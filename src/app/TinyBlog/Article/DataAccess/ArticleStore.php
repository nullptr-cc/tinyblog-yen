<?php

namespace TinyBlog\Article\DataAccess;

use Yada\Driver as SqlDriver;
use TinyBlog\Article\Article;

class ArticleStore
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function insertArticle(Article $article)
    {
        $sql =
            'insert into `article`
             (`author_id`, `title`, `body_raw`, `body_html`, `teaser`, `created_at`)
             values
             (:author_id, :title, :body_raw, :body_html, :teaser, :created_at)';

        $this->sql_driver
             ->prepare($sql)
             ->bindInt(':author_id', $article->getAuthor()->getId())
             ->bindString(':title', $article->getTitle())
             ->bindString(':body_raw', $article->getBody()->getSource())
             ->bindString(':body_html', $article->getBody()->getHtml())
             ->bindString(':teaser', $article->getTeaser())
             ->bindDateTime(':created_at', $article->getCreatedAt())
             ->execute();

        return (object)[
            'id' => $this->sql_driver->lastInsertId()
        ];
    }

    public function updateArticle(Article $article)
    {
        $sql =
            'update `article`
             set `title` = :title,
                 `body_raw` = :body_raw,
                 `body_html` = :body_html,
                 `teaser` = :teaser
             where id = :id';

        $this->sql_driver
             ->prepare($sql)
             ->bindString(':title', $article->getTitle())
             ->bindString(':body_raw', $article->getBody()->getSource())
             ->bindString(':body_html', $article->getBody()->getHtml())
             ->bindString(':teaser', $article->getTeaser())
             ->bindInt(':id', $article->getId())
             ->execute();

        return true;
    }

    public function deleteArticle(Article $article)
    {
        $sql = 'delete from `article` where `id` = :id';

        $this->sql_driver
             ->prepare($sql)
             ->bindInt(':id', $article->getId())
             ->execute();

        return true;
    }
}
