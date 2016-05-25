<?php

namespace TinyBlogTest\Comment;

use Yada\Driver as SqlDriver;
use TinyBlog\Comment\ModuleComment;
use TinyBlog\Comment\CommentRepo;

class ModuleCommentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCommentRepo()
    {
        $sql_driver = $this->prophesize(SqlDriver::class);

        $module = new ModuleComment($sql_driver->reveal());
        $repo = $module->getCommentRepo();

        $this->assertInstanceOf(CommentRepo::class, $repo);
    }
}
