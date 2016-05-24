<?php

namespace TinyBlog\Comment\DataAccess;

use Yada\Driver;
use TinyBlog\Comment\Comment;

class CommentStore
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

    public function deleteComment(Comment $comment)
    {
        $sql = 'delete from `comment` where id = :id';

        $this->driver
             ->prepare($sql)
             ->bindInt(':id', $comment->getId())
             ->execute();

        return true;
    }
}
