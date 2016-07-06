<?php

namespace TinyBlog\User;

use Yada\Driver as SqlDriver;
use TinyBlog\User\DataAccess\UserStore;

class ModuleUser
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getUserRepo()
    {
        return new UserRepo($this->getUserStore());
    }

    private function getUserStore()
    {
        return new UserStore($this->sql_driver);
    }
}
