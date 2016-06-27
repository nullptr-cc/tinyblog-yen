<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Article\Article;
use TinyBlog\Comment\CommentRepo;
use TinyBlog\Comment\Comment;
use TinyBlog\User\User;
use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\Web\Service\CommentEditor;
use DateTimeImmutable;
use Prophecy\Argument;

class CommentEditorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateComment()
    {
        $repo = $this->prophesize(CommentRepo::class);
        $ret_comment = new Comment([
            'id' => 7891,
            'body' => 'Test comment body',
            'author' => new User(['id' => 11]),
            'article' => new Article(['id' => 42]),
            'created_at' => new DateTimeImmutable('@112233')
        ]);
        $repo->persistComment(Argument::that([$this, 'prpCheckComment']))
             ->willReturn($ret_comment);

        $data = new CommentData(42, 'Test comment body');
        $article = new Article(['id' => 42]);
        $author = new User(['id' => 11]);
        $created_at = new DateTimeImmutable('@112233');

        $editor = new CommentEditor($repo->reveal());
        $result = $editor->createComment($data, $article, $author, $created_at);

        $this->assertInstanceOf(Comment::class, $result);
        $this->assertEquals(7891, $result->getId());
        $this->assertEquals('Test comment body', $result->getBody());
        $this->assertEquals(11, $result->getAuthor()->getId());
        $this->assertEquals(42, $result->getArticle()->getId());
        $this->assertEquals(112233, $result->getCreatedAt()->getTimestamp());
    }

    public function prpCheckComment(Comment $comment)
    {
        $this->assertNull($comment->getId());
        $this->assertEquals('Test comment body', $comment->getBody());
        $this->assertEquals(11, $comment->getAuthor()->getId());
        $this->assertEquals(42, $comment->getArticle()->getId());
        $this->assertEquals(112233, $comment->getCreatedAt()->getTimestamp());
        return true;
    }
}
