<?php

namespace TinyBlog\User\DataAccess;

use Yada\Driver as SqlDriver;
use Yada\Statement as SqlStatement;
use TinyBlog\User\User;
use TinyBlog\User\UserRole;

class UserStore
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    /**
     * @return int
     */
    public function countById($id)
    {
        $sql = 'select count(*) from `user` where id = :id';

        return
            $this->sql_driver
                 ->prepare($sql)
                 ->bindInt(':id', $id)
                 ->execute()
                 ->fetchColumn();
    }

    /**
     * @return int
     */
    public function countByUsername($username)
    {
        $sql = 'select count(*) from `user` where username = :username';

        return
            $this->sql_driver
                 ->prepare($sql)
                 ->bindString(':username', $username)
                 ->execute()
                 ->fetchColumn();
    }

    /**
     * @return User[]
     */
    public function fetchById($id)
    {
        $sql = 'select * from user where id = :id';

        $stmt =
            $this->sql_driver
                 ->prepare($sql)
                 ->bindInt(':id', $id)
                 ->execute();

        return $this->makeResult($stmt);
    }

    /**
     * @return User[]
     */
    public function fetchByUsername($username)
    {
        $sql = 'select * from user where username = :username';

        $stmt =
            $this->sql_driver
                 ->prepare($sql)
                 ->bindString(':username', $username)
                 ->execute();

        return $this->makeResult($stmt);
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

        $this->sql_driver
             ->prepare($sql)
             ->bindString(':username', $user->getUsername())
             ->bindString(':nickname', $user->getNickname())
             ->bindString(':password', $user->getPassword())
             ->bindString(':role', $user->getRole()->value())
             ->execute();

        return (object)[
            'id' => $this->sql_driver->lastInsertId()
        ];
    }

    private function makeResult(SqlStatement $stmt)
    {
        $result = [];

        while ($row = $stmt->fetch()) {
            $result[] = $this->makeUser($row);
        };

        return $result;
    }

    private function makeUser(array $raw)
    {
        $raw['role'] = UserRole::fromValue($raw['role']);

        return new User($raw);
    }
}
