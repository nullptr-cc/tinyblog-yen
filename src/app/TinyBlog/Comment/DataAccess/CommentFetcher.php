<?php

namespace TinyBlog\Comment\DataAccess;

use Yada\Driver;
use TinyBlog\Article\Article;
use TinyBlog\User\User;
use TinyBlog\Comment\Comment;
use DateTimeImmutable;

class CommentFetcher
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function fetchByArticle(Article $article, array $order = [])
    {
        $sql = sprintf(
            'select c.*, u.id as author_id, u.nickname
             from `comment` as c
             inner join `user` as u on u.id = c.author_id
             where c.article_id = :article_id
             %s',
            $this->makeOrderString($order)
        );

        $stmt =
            $this->driver
                 ->prepare($sql)
                 ->bindInt(':article_id', $article->getId())
                 ->execute();

        return $this->makeResult($stmt->fetchAll(), $article);
    }

    protected function makeResult(array $rows, Article $article)
    {
        $comments = [];

        foreach ($rows as $row) {
            $comments[] = $this->makeCommentWithAuthor($row)->withArticle($article);
        };

        return $comments;
    }

    protected function makeCommentWithAuthor(array $row)
    {
        $author = new User([
            'id' => $row['author_id'],
            'nickname' => $row['nickname']
        ]);

        $comment = new Comment([
            'id' => $row['id'],
            'author' => $author,
            'body' => $row['body'],
            'created_at' => new DateTimeImmutable($row['created_at'] . 'Z')
        ]);

        return $comment;
    }

    protected function makeOrderString(array $order)
    {
        if (!count($order)) {
            return '';
        };

        $str = [];
        foreach ($order as $key => $dir) {
            $str[] = $key . ' ' . $dir;
        };

        return 'order by ' . implode(', ', $str);
    }
}
