<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\ArticleData;
use Yen\Http\ServerRequest;

class ArticleDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $data = new ArticleData(42, 'Test article', 'Article body, **foo** bar.');

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test article', $data->getTitle());
        $this->assertEquals('Article body, **foo** bar.', $data->getBody());
    }

    public function testCreateFromRequest()
    {
        $post = [
            'article_id' => '42',
            'title' => 'Test article',
            'body' => 'Article body, **foo** bar.'
        ];
        $request = ServerRequest::createFromGlobals([], [], $post);

        $data = ArticleData::createFromRequest($request);

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test article', $data->getTitle());
        $this->assertEquals('Article body, **foo** bar.', $data->getBody());
    }
}
