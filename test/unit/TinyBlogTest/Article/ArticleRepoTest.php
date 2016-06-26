<?php

namespace TinyBlogTest\Article;

use TinyBlog\Article\ArticleRepo;
use TinyBlog\Article\DataAccess\ArticleFetcher;
use TinyBlog\Article\DataAccess\ArticleStore;
use TinyBlog\Article\Article;
use TinyBlog\Article\Exception\ArticleNotExists;

class ArticleRepoTest extends \PHPUnit_Framework_TestCase
{
    public function testInsert()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);
        $article = new Article(['title' => 'test']);

        $store->insertArticle($article)->willReturn((object)['id' => 42]);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $saved = $repo->insertArticle($article);

        $this->assertNotSame($article, $saved);
        $this->assertEquals(42, $saved->getId());
        $this->assertEquals('test', $saved->getTitle());
    }

    public function testUpdate()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);
        $article = new Article(['id' => 71, 'title' => 'test']);

        $store->updateArticle($article)->willReturn(true);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $saved = $repo->updateArticle($article);

        $this->assertSame($article, $saved);
    }

    public function testDelete()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);
        $article = new Article(['id' => 71, 'title' => 'test']);

        $store->deleteArticle($article)->willReturn(true);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $result = $repo->deleteArticle($article);

        $this->assertTrue($result);
    }

    public function testGetArticlesListRangeEmpty()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->countAll()->willReturn(0);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $result = $repo->getArticlesListRange([], 1, 10);

        $this->assertEquals(0, $result->page_count);
        $this->assertEmpty($result->articles);
    }

    public function testGetArticlesListRangeInvalidPageLess()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->countAll()->willReturn(16);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $result = $repo->getArticlesListRange([], 0, 10);

        $this->assertEquals(2, $result->page_count);
        $this->assertEmpty($result->articles);
    }

    public function testGetArticlesListRangeInvalidPageGreat()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->countAll()->willReturn(16);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $result = $repo->getArticlesListRange([], 5, 10);

        $this->assertEquals(2, $result->page_count);
        $this->assertEmpty($result->articles);
    }

    public function testGetArticlesListRangeCorrect()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->countAll()->willReturn(16);
        $fetcher->fetch([], 0, 10)->willReturn(array_fill(0, 10, new Article()));

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $result = $repo->getArticlesListRange([], 1, 10);

        $this->assertEquals(2, $result->page_count);
        $this->assertCount(10, $result->articles);
        $this->assertContainsOnlyInstancesOf(Article::class, $result->articles);
    }

    public function testGetArticleById()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->fetchById(42)->willReturn([new Article(['id' => 42])]);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $article = $repo->getArticleById(42);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals(42, $article->getId());
    }

    public function testGetArticleByIdException()
    {
        $this->expectException(ArticleNotExists::class);

        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->fetchById(42)->willReturn([]);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());
        $article = $repo->getArticleById(42);
    }

    public function testArticleExists()
    {
        $fetcher = $this->prophesize(ArticleFetcher::class);
        $store = $this->prophesize(ArticleStore::class);

        $fetcher->countById(42)->willReturn(0);

        $repo = new ArticleRepo($store->reveal(), $fetcher->reveal());

        $this->assertFalse($repo->articleExists(42));
    }
}
