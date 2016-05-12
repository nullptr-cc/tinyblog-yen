<?php

namespace TinyBlog\User\DataAccess;

use Yada\Driver;
use TinyBlog\User\User;

class UserStore
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
                 ->bindInt(':id', $id)
                 ->execute();

        return $this->makeResult($stmt->fetchAll());
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
                 ->bindString(':username', $username)
                 ->execute();

        return $this->makeResult($stmt->fetchAll());
    }

    /**
     * @return stdClass {id : int}
     */
    public function insert(User $user)
    {
        $sql = 'insert into `user`
                (`username`, `nickname`, `password`, `role`)
                values
                (:username, :nickname, :password, :role)';

        $this->driver
             ->prepare($sql)
             ->bindString(':username', $user->getUsername())
             ->bindString(':nickname', $user->getNickname())
             ->bindString(':password', $user->getPassword())
             ->bindString(':role', $user->getRole())
             ->execute();

        return (object)[
            'id' => $this->driver->lastInsertId()
        ];
    }

    protected function makeResult(array $rows)
    {
        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->makeUser($row);
        };

        return $result;
    }

    protected function makeUser(array $raw)
    {
        return new User($raw);
    }
}
