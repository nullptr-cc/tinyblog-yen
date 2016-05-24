<?php

namespace TinyBlogTest\Comment\DataAccess;

use TinyBlog\Comment\DataAccess\CommentStore;
use TinyBlog\Comment\Comment;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class CommentStoreTest extends \TestExt\DatabaseTestCase
{
    public function testInsertComment()
    {
        $store = new CommentStore($this->getSqlDriver());
        $comment = $this->prepareComment();
        $result = $store->insertComment($comment);

        $this->assertEquals(2, $result->id);
        $this->assertEquals(2, $this->getConnection()->getRowCount('comment'));

        $query_ds = $this->getConnection()->createQueryTable('comment', 'select * from comment where id = 2');
        $expect_ds = $this->createMySQLXMLDataSet(DBFIXT_PATH . '/comment-inserted.xml')->getTable('comment');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }

    public function testDeleteComment()
    {
        $store = new CommentStore($this->getSqlDriver());
        $comment = new Comment(['id' => 1]);
        $result = $store->deleteComment($comment);

        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('comment'));

        $query_ds = $this->getConnection()->createQueryTable('comment', 'select * from comment where id = 1');
        $expect_ds = $this->createXMLDataSet(DBFIXT_PATH . '/comment-empty.xml')->getTable('comment');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }

    protected function prepareComment()
    {
        $author = new User(['id' => 1]);
        $article = new Article(['id' => 1]);
        $comment = new Comment([
            'article' => $article,
            'author' => $author,
            'body' => 'Test comment',
            'created_at' => new \DateTimeImmutable('2016-01-02 03:04:05Z')
        ]);

        return $comment;
    }
}
