<?php

namespace TinyBlogTest\Web\RequestData;

use TinyBlog\Web\RequestData\SignInData;
use Yen\Http\ServerRequest;

class SignInDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $data = new SignInData('foobar', '$ecReT');

        $this->assertEquals('foobar', $data->getUsername());
        $this->assertEquals('$ecReT', $data->getPassword());
    }

    public function testCreateFromRequest()
    {
        $post = [
            'username' => 'foobar',
            'password' => '$ecReT'
        ];
        $request = ServerRequest::createFromGlobals([], [], $post);

        $data = SignInData::createFromRequest($request);

        $this->assertEquals('foobar', $data->getUsername());
        $this->assertEquals('$ecReT', $data->getPassword());
    }
}
