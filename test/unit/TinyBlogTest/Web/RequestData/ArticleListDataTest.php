<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\ArticleListData;
use Yen\Http\ServerRequest;

class ArticleListDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $data = new ArticleListData(3);

        $this->assertEquals(3, $data->getPageNum());
    }

    public function testCreateFromRequest()
    {
        $get = ['page' => '3'];
        $request = ServerRequest::createFromGlobals([], $get);

        $data = ArticleListData::createFromRequest($request);

        $this->assertEquals(3, $data->getPageNum());
    }

    public function testCreateFromEmptyRequest()
    {
        $request = ServerRequest::createFromGlobals();

        $data = ArticleListData::createFromRequest($request);

        $this->assertEquals(1, $data->getPageNum());
    }
}
