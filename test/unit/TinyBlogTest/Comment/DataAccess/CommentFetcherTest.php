<?php

namespace TinyBlogTest\Comment\DataAccess;

use TinyBlog\Comment\DataAccess\CommentFetcher;
use TinyBlog\Comment\Comment;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class CommentFetcherTest extends \TestExt\DatabaseTestCase
{
    public function testFetchByArticle()
    {
        $fetcher = new CommentFetcher($this->getSqlDriver());
        $article = new Article(['id' => 1]);
        
        $result = $fetcher->fetchByArticle($article, ['created_at' => 'asc']);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Comment::class, $result);
        $this->assertInstanceOf(User::class, $result[0]->getAuthor());
        $this->assertSame($article, $result[0]->getArticle());

        $result = $fetcher->fetchByArticle($article);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Comment::class, $result);
        $this->assertInstanceOf(User::class, $result[0]->getAuthor());
        $this->assertSame($article, $result[0]->getArticle());
    }
}
