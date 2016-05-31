<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\ArticleDeleteData;
use Yen\Http\ServerRequest;

class ArticleDeleteDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $data = new ArticleDeleteData(42);

        $this->assertEquals(42, $data->getArticleId());
    }

    public function testCreateFromRequest()
    {
        $post = ['article_id' => '42'];
        $request = ServerRequest::createFromGlobals([], [], $post);

        $data = ArticleDeleteData::createFromRequest($request);

        $this->assertEquals(42, $data->getArticleId());
    }
}
