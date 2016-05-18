<?php

namespace TinyBlogTest\Article;

use Yada\Driver as SqlDriver;
use TinyBlog\Article\ModuleArticle;
use TinyBlog\Article\ArticleRepo;

class ModuleArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArticleRepo()
    {
        $sql_driver = $this->prophesize(SqlDriver::class);

        $module = new ModuleArticle($sql_driver->reveal());
        $repo = $module->getArticleRepo();

        $this->assertInstanceOf(ArticleRepo::class, $repo);
    }
}
