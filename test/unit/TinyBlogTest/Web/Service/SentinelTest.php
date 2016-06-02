<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Web\Service\Sentinel;
use Yen\Http\ServerRequest;

class SentinelTest extends \PHPUnit_Framework_TestCase
{
    protected $func;

    protected function setUp()
    {
        $this->func = (new \Defunc\Builder())->in('TinyBlog\Web\Service');
    }

    protected function tearDown()
    {
        $this->func->clear();
    }

    public function testCreateException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No RNG');

        $this->func->function_exists('random_bytes')->willReturn(false);
        $this->func->function_exists('openssl_random_pseudo_bytes')->willReturn(false);

        $sentinel = new Sentinel('foobar.net');
    }

    public function testGetCsrfTokenName()
    {
        $sentinel = new Sentinel('foobar.net');

        $this->assertEquals('csrftkn', $sentinel->getCsrfTokenName());
    }

    public function testNewCsrfToken()
    {
        $sentinel = new Sentinel('foobar.net');

        $token0 = $sentinel->newCsrfToken();
        $token1 = $sentinel->newCsrfToken();
        $token2 = $sentinel->newCsrfToken();

        $rx = '~^[a-zA-Z0-9/+]{53,}$~';

        $this->assertRegexp($rx, $token0);
        $this->assertRegexp($rx, $token1);
        $this->assertRegexp($rx, $token2);
        $this->assertTrue($token0 != $token1);
        $this->assertTrue($token0 != $token2);
        $this->assertTrue($token1 != $token2);
    }

    public function testShallNotPassHappyPath()
    {
        $sentinel = new Sentinel('foobar.net');

        $env = [
            'HTTP_X_CSRFTKN' => 'test-token',
            'HTTP_REFERER' => 'http://foobar.net/page'
        ];
        $request = ServerRequest::createFromGlobals($env, [], ['csrftkn' => 'test-token']);
        $this->assertFalse($sentinel->shallNotPass($request));

        $env = [
            'HTTP_X_CSRFTKN' => 'test-token',
            'HTTP_ORIGIN' => 'http://foobar.net'
        ];
        $request = ServerRequest::createFromGlobals($env, [], ['csrftkn' => 'test-token']);
        $this->assertFalse($sentinel->shallNotPass($request));
    }

    public function testShallNotPassFail()
    {
        $sentinel = new Sentinel('foobar.net');

        $request = ServerRequest::createFromGlobals();
        $this->assertTrue($sentinel->shallNotPass($request));

        $request = ServerRequest::createFromGlobals(['HTTP_REFERER' => 'http://foobar.net/page']);
        $this->assertTrue($sentinel->shallNotPass($request));

        $env = ['HTTP_REFERER' => 'http://foobar.net/page'];
        $post = ['csrftkn' => 'test-token'];
        $request = ServerRequest::createFromGlobals($env, [], $post);
        $this->assertTrue($sentinel->shallNotPass($request));

        $env = ['HTTP_REFERER' => 'http://foobar.net/page', 'HTTP_X_CSRFTKN' => 'fail-token'];
        $post = ['csrftkn' => 'test-token'];
        $request = ServerRequest::createFromGlobals($env, [], $post);
        $this->assertTrue($sentinel->shallNotPass($request));
    }
}
