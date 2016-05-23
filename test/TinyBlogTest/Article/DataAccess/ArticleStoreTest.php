<?php

namespace TinyBlogTest\Article\DataAccess;

use TinyBlog\Article\DataAccess\ArticleStore;
use TinyBlog\Article\Article;
use TinyBlog\Article\Content;
use TinyBlog\User\User;
use DateTimeImmutable;

class ArticleStoreTest extends \TestExt\DatabaseTestCase
{
    public function testInsertArticle()
    {
        $store = new ArticleStore($this->getSqlDriver());
        $article = new Article([
            'author' => new User(['id' => 1]),
            'title' => 'Test article',
            'body' => new Content('foo bar', 'foo<br>bar'),
            'teaser' => 'foo',
            'created_at' => new DateTimeImmutable('2016-01-02 03:04:56Z')
        ]);

        $result = $store->insertArticle($article);

        $this->assertEquals(2, $result->id);
        $this->assertEquals(2, $this->getConnection()->getRowCount('article'));

        $query_ds = $this->getConnection()->createQueryTable('article', 'select * from article where id = 2');
        $expect_ds = $this->createMySQLXMLDataSet(DBFIXT_PATH . '/article-inserted.xml')->getTable('article');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }

    public function testUpdateArticle()
    {
        $store = new ArticleStore($this->getSqlDriver());
        $article = new Article([
            'id' => 1,
            'author' => new User(['id' => 1]),
            'title' => 'Test article',
            'body' => new Content('foo bar', 'foo<br>bar'),
            'teaser' => 'foo',
            'created_at' => new DateTimeImmutable('2016-01-02 03:04:56Z')
        ]);

        $result = $store->updateArticle($article);

        $this->assertTrue($result);
        $this->assertEquals(1, $this->getConnection()->getRowCount('article'));

        $query_ds = $this->getConnection()->createQueryTable('article', 'select * from article where id = 1');
        $expect_ds = $this->createMySQLXMLDataSet(DBFIXT_PATH . '/article-updated.xml')->getTable('article');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }

    public function testDeleteArticle()
    {
        $store = new ArticleStore($this->getSqlDriver());
        $article = new Article(['id' => 1]);

        $result = $store->deleteArticle($article);

        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('article'));

        $query_ds = $this->getConnection()->createQueryTable('article', 'select * from article where id = 1');
        $expect_ds = $this->createXMLDataSet(DBFIXT_PATH . '/article-empty.xml')->getTable('article');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }
}
