<?php

namespace TinyBlog\DataAccess\User;

use Yada\Driver;
use TinyBlog\Type\User;

class UserFetcher
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return User[]
     */
    public function fetchById($id)
    {
        $sql = 'select * from user where id = :id';

        $stmt =
            $this->driver
                 ->prepare($sql)
                 ->bindValue(':id', $id, \PDO::PARAM_INT)
                 ->execute();

        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->makeUser($row);
        };

        return $result;
    }

    /**
     * @return User[]
     */
    public function fetchByUsername($username)
    {
        $sql = 'select * from user where username = :username';

        $stmt =
            $this->driver
                 ->prepare($sql)
                 ->bindValue(':username', $username, \PDO::PARAM_STR)
                 ->execute();

        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->makeUser($row);
        };

        return $result;
    }

    protected function makeUser(array $raw)
    {
        return new User($raw);
    }
}
