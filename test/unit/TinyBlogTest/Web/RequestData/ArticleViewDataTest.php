<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\ArticleViewData;
use Yen\Http\ServerRequest;

class ArticleViewDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $data = new ArticleViewData(42);

        $this->assertEquals(42, $data->getArticleId());
    }

    public function testCreateFromRequest()
    {
        $get = ['article_id' => '42'];
        $request = ServerRequest::createFromGlobals([], $get);

        $data = ArticleViewData::createFromRequest($request);

        $this->assertEquals(42, $data->getArticleId());
    }
}
