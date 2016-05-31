<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Article\Article;
use TinyBlog\Web\Service\UrlBuilder;
use Yen\Router\Contract\IRouter;
use Yen\Http\Contract\IUri;
use Yen\Http\Uri;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildArticleUrl()
    {
        $router = $this->prophesize(IRouter::class);
        $resolved = (object)['uri' => '/a/42', 'args' => []];
        $router->resolve('article', ['article_id' => 42])
               ->willReturn($resolved);

        $base_url = Uri::createFromString('http://tinyblog.net');

        $url_builder = new UrlBuilder($router->reveal(), $base_url);
        $article = new Article(['id' => 42]);
        $article_url = $url_builder->buildArticleUrl($article);

        $this->assertInstanceOf(IUri::class, $article_url);
        $this->assertEquals('http://tinyblog.net/a/42', $article_url->__toString());
    }

    public function testBuildMainPageUrl()
    {
        $router = $this->prophesize(IRouter::class);
        $base_url = Uri::createFromString('http://tinyblog.net');

        $url_builder = new UrlBuilder($router->reveal(), $base_url);
        $mp_url = $url_builder->buildMainPageUrl(['foo' => 'bar']);

        $this->assertInstanceOf(IUri::class, $mp_url);
        $this->assertEquals('http://tinyblog.net/?foo=bar', $mp_url->__toString());
    }
}
