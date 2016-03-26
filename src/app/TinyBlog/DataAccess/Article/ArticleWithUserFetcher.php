<?php

namespace TinyBlog\DataAccess\Article;

use Yada\Driver;
use TinyBlog\Type\Article;
use TinyBlog\Type\User;
use TinyBlog\Type\Content;
use DateTimeImmutable;

class ArticleWithUserFetcher
{
    protected $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function findById($id)
    {
        $sql =
            'select a.*, u.nickname from article as a inner join user as u on u.id = a.author_id' .
            ' where a.id = :id';

        $row =
            $this->sql_driver
                 ->prepare($sql)
                 ->bindValue(':id', $id, \PDO::PARAM_INT)
                 ->execute()
                 ->fetch();

        return $this->makeArticleWithUser($row);
    }

    public function count()
    {
        return $this->sql_driver->query('select count(*) from article')->fetchColumn();
    }

    public function find(array $order = [], $skip = null, $limit = null)
    {
        $sql = sprintf(
            'select a.*, u.nickname
             from article as a
             inner join user as u on u.id = a.author_id
             %s %s',
            $this->makeOrderString($order),
            $this->makeLimitString($skip, $limit)
        );

        $rows = $this->sql_driver->query($sql)->fetchAll();
        $articles = [];
        foreach ($rows as $row) {
            $articles[] = $this->makeArticleWithUser($row);
        };

        return $articles;
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

    protected function makeLimitString($skip = null, $limit = null)
    {
        if ($limit == null && $skip == null) {
            return '';
        };

        return sprintf('limit %d, %d', $skip, $limit);
    }

    protected function makeArticleWithUser(array $data)
    {
        $author = new User([
            'id' => $data['author_id'],
            'nickname' => $data['nickname']
        ]);

        $article = new Article([
            'id' => $data['id'],
            'author' => $author,
            'title' => $data['title'],
            'body' => new Content($data['body_raw'], $data['body_html']),
            'teaser' => $data['teaser'],
            'created_at' => new DateTimeImmutable($data['created_at'] . 'Z')
        ]);

        return $article;
    }
}
