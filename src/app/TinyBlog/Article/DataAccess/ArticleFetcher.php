<?php

namespace TinyBlog\Article\DataAccess;

use Yada\Driver as SqlDriver;
use Yada\Statement as SqlStatement;
use TinyBlog\Article\Article;
use TinyBlog\Article\Content;
use TinyBlog\User\User;
use DateTimeImmutable;

class ArticleFetcher
{
    protected $driver;

    public function __construct(SqlDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return Article[]
     */
    public function fetchById($id)
    {
        $sql =
            'select a.*, u.nickname ' .
            'from article as a ' .
            'inner join user as u on u.id = a.author_id ' .
            'where a.id = :id';

        $stmt =
            $this->driver
                 ->prepare($sql)
                 ->bindInt(':id', $id)
                 ->execute();

        return $this->makeResult($stmt);
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->driver->query('select count(*) from article')->fetchColumn();
    }

    /**
     * @return int
     */
    public function count(array $cond)
    {
        $sql = sprintf(
            'select count(*) from article where %s',
            $this->makeWhere($cond)
        );

        return
            $this->driver
                 ->prepare($sql)
                 ->bindAuto($cond)
                 ->execute()
                 ->fetchColumn();
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

        return $this->makeResult($stmt);
    }

    /**
     * @return string
     */
    protected function makeWhere(array $cond)
    {
        if (!count($cond)) {
            return '1';
        };

        $where = [];

        foreach ($cond as $key => $val) {
            $where[] = sprintf('%s = :%s', $key, $key);
        };

        return implode(' and ', $where);
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

    /**
     * @return Article[]
     */
    protected function makeResult(SqlStatement $stmt)
    {
        $articles = [];

        while ($row = $stmt->fetch()) {
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
