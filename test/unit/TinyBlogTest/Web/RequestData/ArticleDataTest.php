<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\ArticleData;
use Yen\Http\ServerRequest;

class ArticleDataTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $data = new ArticleData();

        $this->assertEquals(0, $data->getArticleId());
        $this->assertEquals('', $data->getTitle());
        $this->assertEquals('', $data->getBody());
        $this->assertNull($data->getCreatedAt());
    }

    public function testGetters()
    {
        $data = new ArticleData(42, 'Test article', 'Article body, **foo** bar.', new \DateTime('@789'));

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test article', $data->getTitle());
        $this->assertEquals('Article body, **foo** bar.', $data->getBody());
        $this->assertEquals(789, $data->getCreatedAt()->getTimestamp());
    }

    public function testCreateFromRequest()
    {
        $post = [
            'article_id' => 42,
            'title' => 'Test article',
            'body' => 'Article body, **foo** bar.'
        ];
        $request = ServerRequest::createFromGlobals([], [], $post);

        $data = ArticleData::createFromRequest($request);

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test article', $data->getTitle());
        $this->assertEquals('Article body, **foo** bar.', $data->getBody());
        $this->assertNull($data->getCreatedAt());
    }

    public function testWithCreatedAt()
    {
        $data = new ArticleData(42, 'Test article', 'Article body, **foo** bar.', new \DateTime('@789'));
        $clone = $data->withCreatedAt(new \DateTime('@11111'));

        $this->assertInstanceOf(ArticleData::class, $clone);
        $this->assertEquals(11111, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals(42, $clone->getArticleId());
        $this->assertEquals('Test article', $clone->getTitle());
        $this->assertEquals('Article body, **foo** bar.', $clone->getBody());
        $this->assertEquals(789, $data->getCreatedAt()->getTimestamp());
    }
}
