<?php

namespace TinyBlogTest\Tools;

use TinyBlog\Tools\ModuleTools;
use TinyBlog\Tools\IMarkdownTransformer;
use TinyBlog\Tools\TeaserMaker;
use Yen\HttpClient\Contract\IHttpClient;

class ModuleToolsTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $tools = new ModuleTools();

        $this->assertInstanceOf(IMarkdownTransformer::class, $tools->getMarkdownTransformer());
        $this->assertInstanceOf(TeaserMaker::class, $tools->getTeaserMaker());
        $this->assertInstanceOf(IHttpClient::class, $tools->getHttpClient());
    }
}
