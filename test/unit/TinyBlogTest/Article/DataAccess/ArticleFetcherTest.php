<?php

namespace TinyBlogTest\Article\DataAccess;

use TinyBlog\Article\DataAccess\ArticleFetcher;
use TinyBlog\Article\Article;

class ArticleFetcherTest extends \TestExt\DatabaseTestCase
{
    public function testFetchById()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());
        $result = $fetcher->fetchById(1);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Article::class, $result);
        $this->assertEquals(1, $result[0]->getId());
    }

    public function testFetchByIdEmpty()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());
        $result = $fetcher->fetchById(10);

        $this->assertCount(0, $result);
    }

    public function testCountAll()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());

        $this->assertEquals(1, $fetcher->countAll());
    }

    public function testCountById()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());

        $this->assertEquals(1, $fetcher->countById(1));
        $this->assertEquals(0, $fetcher->countById(101));
    }

    public function testFetch()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());
        $result = $fetcher->fetch();

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Article::class, $result);
        $this->assertEquals(1, $result[0]->getId());
    }

    public function testFetchOrdered()
    {
        $fetcher = new ArticleFetcher($this->getSqlDriver());
        $result = $fetcher->fetch(['created_at' => 'desc'], 0, 5);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Article::class, $result);
        $this->assertEquals(1, $result[0]->getId());
    }
}
