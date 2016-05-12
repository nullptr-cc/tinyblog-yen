<?php

namespace TinyBlog\User;

use Yada\Driver;

class ModuleUser
{
    private $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getUserRepo()
    {
        return new UserRepo($this->getUserStore());
    }

    private function getUserStore()
    {
        return new DataAccess\UserStore($this->sql_driver);
    }
}
