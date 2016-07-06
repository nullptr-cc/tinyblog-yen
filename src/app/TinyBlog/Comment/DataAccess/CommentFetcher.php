<?php

namespace TinyBlog\Comment\DataAccess;

use Yada\Driver as SqlDriver;
use Yada\Statement as SqlStatement;
use TinyBlog\Article\Article;
use TinyBlog\User\User;
use TinyBlog\Comment\Comment;
use DateTimeImmutable;

class CommentFetcher
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
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
            $this->sql_driver
                 ->prepare($sql)
                 ->bindInt(':article_id', $article->getId())
                 ->execute();

        return $this->makeResult($stmt, $article);
    }

    private function makeResult(SqlStatement $stmt, Article $article)
    {
        $comments = [];

        while ($row = $stmt->fetch()) {
            $comments[] = $this->makeCommentWithAuthor($row)->withArticle($article);
        };

        return $comments;
    }

    private function makeCommentWithAuthor(array $row)
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

    private function makeOrderString(array $order)
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
