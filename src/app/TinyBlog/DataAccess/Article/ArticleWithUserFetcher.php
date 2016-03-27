<?php

namespace TinyBlog\DataAccess\Article;

use Yada\Driver;
use TinyBlog\Type\Article;
use TinyBlog\Type\User;
use TinyBlog\Type\Content;
use DateTimeImmutable;

class ArticleWithUserFetcher
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return Article[]
     */
    public function fetchById($id)
    {
        $sql =
            'select a.*, u.nickname from article as a inner join user as u on u.id = a.author_id' .
            ' where a.id = :id';

        $stmt =
            $this->driver
                 ->prepare($sql)
                 ->bindValue(':id', $id, \PDO::PARAM_INT)
                 ->execute();

        return $this->makeResult($stmt->fetchAll());
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->driver->query('select count(*) from article')->fetchColumn();
    }

    /**
     * @return Article[]
     */
    public function fetch(array $order = [], $skip = null, $limit = null)
    {
        $sql = sprintf(
            'select a.*, u.nickname
             from article as a
             inner join user as u on u.id = a.author_id
             %s %s',
            $this->makeOrderString($order),
            $this->makeLimitString($skip, $limit)
        );

        $stmt = $this->driver->query($sql);

        return $this->makeResult($stmt->fetchAll());
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

    protected function makeResult(array $rows)
    {
        $articles = [];

        foreach ($rows as $row) {
            $articles[] = $this->makeArticleWithUser($row);
        };

        return $articles;
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
