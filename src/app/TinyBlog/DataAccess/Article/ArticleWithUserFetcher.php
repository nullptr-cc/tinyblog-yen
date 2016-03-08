<?php

namespace TinyBlog\DataAccess\Article;

use Yada\Driver;

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

        return $row;
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

        return $this->sql_driver->query($sql)->fetchAll();
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
}
