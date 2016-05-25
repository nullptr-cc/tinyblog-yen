<?php

namespace TinyBlogTest\Tools;

use TinyBlog\Tools\MichelfMarkdownTransformer;
use Michelf\MarkdownInterface;

class MichelfMarkdownTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $md = $this->prophesize(MarkdownInterface::class);
        $md->transform('**foo**')->willReturn('<strong>foo</strong>');

        $transformer = new MichelfMarkdownTransformer($md->reveal());
        $result = $transformer->toHtml('**foo**');

        $this->assertEquals('<strong>foo</strong>', $result);
    }
}
