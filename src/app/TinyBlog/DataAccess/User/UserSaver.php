<?php

namespace TinyBlog\DataAccess\User;

use Yada\Driver;
use TinyBlog\Type\User;

class UserSaver
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function insert(User $user)
    {
        $sql = 'insert into `user`
                (`username`, `nickname`, `password`)
                values
                (:username, :nickname, :password)';

        $this->driver
             ->prepare($sql)
             ->bindString(':username', $user->getUsername())
             ->bindString(':nickname', $user->getNickname())
             ->bindString(':password', $user->getPassword())
             ->execute();

        return (object)[
            'id' => $this->driver->lastInsertId()
        ];
    }
}
