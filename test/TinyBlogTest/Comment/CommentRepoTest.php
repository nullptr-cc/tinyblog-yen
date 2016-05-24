<?php

namespace TinyBlogTest\Comment;

use TinyBlog\Comment\CommentRepo;
use TinyBlog\Comment\Comment;
use TinyBlog\Comment\DataAccess\CommentFetcher;
use TinyBlog\Comment\DataAccess\CommentStore;
use TinyBlog\Article\Article;

class CommentRepoTest extends \PHPUnit_Framework_TestCase
{
    public function testPersistCommentInsert()
    {
        $store = $this->prophesize(CommentStore::class);
        $fetcher = $this->prophesize(CommentFetcher::class);
        $comment = new Comment(['body' => 'test comment']);

        $store->insertComment($comment)->willReturn((object)['id' => 99]);

        $repo = new CommentRepo($store->reveal(), $fetcher->reveal());
        $saved = $repo->persistComment($comment);

        $this->assertInstanceOf(Comment::class, $saved);
        $this->assertNotSame($saved, $comment);
        $this->assertEquals(99, $saved->getId());
        $this->assertEquals('test comment', $saved->getBody());
    }

    public function testDeleteComment()
    {
        $store = $this->prophesize(CommentStore::class);
        $fetcher = $this->prophesize(CommentFetcher::class);
        $comment = new Comment(['id' => 99, 'body' => 'test comment']);

        $store->deleteComment($comment)->willReturn(true);

        $repo = new CommentRepo($store->reveal(), $fetcher->reveal());

        $this->assertTrue($repo->deleteComment($comment));
    }

    public function testGetArticleComments()
    {
        $store = $this->prophesize(CommentStore::class);
        $fetcher = $this->prophesize(CommentFetcher::class);
        $article = new Article(['id' => 77]);

        $fetcher->fetchByArticle($article, ['created_at' => 'asc'])
                ->willReturn(array_fill(0, 5, new Comment()));

        $repo = new CommentRepo($store->reveal(), $fetcher->reveal());
        $comments = $repo->getArticleComments($article);

        $this->assertCount(5, $comments);
        $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    }
}
