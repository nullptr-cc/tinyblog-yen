<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\CommentData;
use Yen\Http\ServerRequest;

class CommentDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $data = new CommentData(42, 'Test comment text');

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test comment text', $data->getBody());
    }

    public function testCreateFromRequest()
    {
        $post = [
            'article_id' => '42',
            'body' => 'Test comment text'
        ];
        $request = ServerRequest::createFromGlobals([], [], $post);

        $data = CommentData::createFromRequest($request);

        $this->assertEquals(42, $data->getArticleId());
        $this->assertEquals('Test comment text', $data->getBody());
    }
}
