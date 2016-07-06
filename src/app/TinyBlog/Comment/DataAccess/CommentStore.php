<?php

namespace TinyBlog\Comment\DataAccess;

use Yada\Driver as SqlDriver;
use TinyBlog\Comment\Comment;

class CommentStore
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    /**
     * @return stdClass { id : int }
     */
    public function insertComment(Comment $comment)
    {
        $sql = 'insert into `comment`
               (`article_id`, `author_id`, `body`, `created_at`)
               values
               (:article_id, :author_id, :body, :created_at)';

        $this->sql_driver
             ->prepare($sql)
             ->bindInt(':article_id', $comment->getArticle()->getId())
             ->bindInt(':author_id', $comment->getAuthor()->getId())
             ->bindString(':body', $comment->getBody())
             ->bindDateTime(':created_at', $comment->getCreatedAt())
             ->execute();

        return (object)[
            'id' => $this->sql_driver->lastInsertId()
        ];
    }

    public function deleteComment(Comment $comment)
    {
        $sql = 'delete from `comment` where id = :id';

        $this->sql_driver
             ->prepare($sql)
             ->bindInt(':id', $comment->getId())
             ->execute();

        return true;
    }
}
