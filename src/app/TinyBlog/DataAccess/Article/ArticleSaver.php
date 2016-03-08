<?php

namespace TinyBlog\DataAccess\Article;

use Yada\Driver;
use TinyBlog\Type\IArticle;

class ArticleSaver
{
    protected $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function insertArticle(IArticle $article)
    {
        $sql =
            'insert into `article`
             (`author_id`, `title`, `body_raw`, `body_html`, `teaser`, `created_at`)
             values
             (:author_id, :title, :body_raw, :body_html, :teaser, :created_at)';

        $this->sql_driver
             ->prepare($sql)
             ->bindValue(':author_id', $article->getAuthor()->getId(), \PDO::PARAM_INT)
             ->bindValue(':title', $article->getTitle(), \PDO::PARAM_STR)
             ->bindValue(':body_raw', $article->getBody()->getSource(), \PDO::PARAM_STR)
             ->bindValue(':body_html', $article->getBody()->getHtml(), \PDO::PARAM_STR)
             ->bindValue(':teaser', $article->getTeaser(), \PDO::PARAM_STR)
             ->bindValue(':created_at', $article->getCreatedAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR)
             ->execute();

        return (object)[
            'id' => $this->sql_driver->lastInsertId()
        ];
    }

    public function updateArticle(IArticle $article)
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
             ->bindValue(':title', $article->getTitle(), \PDO::PARAM_STR)
             ->bindValue(':body_raw', $article->getBody()->getSource(), \PDO::PARAM_STR)
             ->bindValue(':body_html', $article->getBody()->getHtml(), \PDO::PARAM_STR)
             ->bindValue(':teaser', $article->getTeaser(), \PDO::PARAM_STR)
             ->bindValue(':id', $article->getId(), \PDO::PARAM_INT)
             ->execute();

        return true;
    }

    public function deleteArticle(IArticle $article)
    {
        $sql = 'delete from `article` where `id` = :id';

        $this->sql_driver
             ->prepare($sql)
             ->bindValue(':id', $article->getId(), \PDO::PARAM_INT)
             ->execute();

        return true;
    }
}
