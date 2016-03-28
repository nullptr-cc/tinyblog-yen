<?php

namespace TinyBlog\DataAccess\Comment;

use Yada\Driver;
use TinyBlog\Type\Comment;

class CommentSaver
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
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

        $this->driver
             ->prepare($sql)
             ->bindInt(':article_id', $comment->getArticle()->getId())
             ->bindInt(':author_id', $comment->getAuthor()->getId())
             ->bindString(':body', $comment->getBody())
             ->bindDateTime(':created_at', $comment->getCreatedAt())
             ->execute();

        return (object)[
            'id' => $this->driver->lastInsertId()
        ];
    }

    public function updateComment(Comment $comment)
    {
        $sql = 'update `comment`
                set `article_id` = :article_id,
                    `author_id` = :author_id,
                    `body` = :body,
                    `created_at` = :created_at
                where id = :id';

        $this->driver
             ->preapre($sql)
             ->bindInt(':article_id', $comment->getArticle()->getId())
             ->bindInt(':author_id', $comment->getAuthor()->getId())
             ->bindString(':body', $comment->getBody())
             ->bindDateTime(':created_at', $comment->getCreatedAt())
             ->execute();

        return true;
    }

    public function deleteComment(Comment $comment)
    {
        $sql = 'delete from `comment` where id = :id';

        $this->driver
             ->prepare($sql)
             ->binInt(':id', $comment->getId())
             ->execute();

        return true;
    }
}
