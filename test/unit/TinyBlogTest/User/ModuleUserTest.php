<?php

namespace TinyBlogTest\User;

use Yada\Driver as SqlDriver;
use TinyBlog\User\ModuleUser;
use TinyBlog\User\UserRepo;

class ModuleUserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUserRepo()
    {
        $sql_driver = $this->prophesize(SqlDriver::class);

        $module = new ModuleUser($sql_driver->reveal());
        $repo = $module->getUserRepo();

        $this->assertInstanceOf(UserRepo::class, $repo);
    }
}
