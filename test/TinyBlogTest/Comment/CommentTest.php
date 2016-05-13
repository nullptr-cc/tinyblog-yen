<?php

namespace TinyBlogTest\Comment;

use TinyBlog\Comment\Comment;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $comment = new Comment();

        $this->assertNull($comment->getId());
        $this->assertEquals('', $comment->getBody());
        $this->assertNull($comment->getCreatedAt());
        $this->assertInstanceOf(Article::class, $comment->getArticle());
        $this->assertInstanceOf(User::class, $comment->getAuthor());
    }

    public function testGetters()
    {
        $comment = $this->prepareComment();

        $this->assertEquals(93, $comment->id());
        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->body());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->created()->getTimestamp());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->author()->getId());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->article()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    public function testWithId()
    {
        $comment = $this->prepareComment();

        $clone = $comment->withId(99);

        $this->assertEquals(99, $clone->getId());
        $this->assertEquals('test comment', $clone->getBody());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $clone->getAuthor()->getId());
        $this->assertEquals(37, $clone->getArticle()->getId());

        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    public function testWithBody()
    {
        $comment = $this->prepareComment();

        $clone = $comment->withBody('foo bar');

        $this->assertEquals(93, $clone->getId());
        $this->assertEquals('foo bar', $clone->getBody());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $clone->getAuthor()->getId());
        $this->assertEquals(37, $clone->getArticle()->getId());

        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    public function testWithCreatedAt()
    {
        $comment = $this->prepareComment();

        $clone = $comment->withCreatedAt(new \DateTimeImmutable('@1111111111'));

        $this->assertEquals(93, $clone->getId());
        $this->assertEquals('test comment', $clone->getBody());
        $this->assertEquals(1111111111, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $clone->getAuthor()->getId());
        $this->assertEquals(37, $clone->getArticle()->getId());

        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    public function testWithArticle()
    {
        $comment = $this->prepareComment();

        $clone = $comment->withArticle(new Article(['id' => 73]));

        $this->assertEquals(93, $clone->getId());
        $this->assertEquals('test comment', $clone->getBody());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $clone->getAuthor()->getId());
        $this->assertEquals(73, $clone->getArticle()->getId());

        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    public function testWithAuthor()
    {
        $comment = $this->prepareComment();

        $clone = $comment->withAuthor(new User(['id' => 24]));

        $this->assertEquals(93, $clone->getId());
        $this->assertEquals('test comment', $clone->getBody());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(24, $clone->getAuthor()->getId());
        $this->assertEquals(37, $clone->getArticle()->getId());

        $this->assertEquals(93, $comment->getId());
        $this->assertEquals('test comment', $comment->getBody());
        $this->assertEquals(1451618627, $comment->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $comment->getAuthor()->getId());
        $this->assertEquals(37, $comment->getArticle()->getId());
    }

    private function prepareComment()
    {
        $author = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar'
        ]);

        $article = new Article([
            'id' => 37,
            'title' => 'Title'
        ]);

        $comment = new Comment([
            'id' => 93,
            'body' => 'test comment',
            'created_at' => new \DateTimeImmutable('@1451618627'),
            'author' => $author,
            'article' => $article
        ]);

        return $comment;
    }
}
