<?php

namespace TinyBlog\DataAccess\User;

use Yada\Driver;

class UserFetcher
{
    protected $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function findById($id)
    {
        $sql = 'select * from user where id = :id';

        $row =
            $this->sql_driver
                 ->prepare($sql)
                 ->bindValue(':id', $id, \PDO::PARAM_INT)
                 ->execute()
                 ->fetch();

        return $row;
    }

    public function findByUsername($username)
    {
        $sql = 'select * from user where username = :username';

        $row =
            $this->sql_driver
                 ->prepare($sql)
                 ->bindValue(':username', $username, \PDO::PARAM_STR)
                 ->execute()
                 ->fetch();

        return $row;
    }
}
