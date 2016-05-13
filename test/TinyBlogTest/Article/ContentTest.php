<?php

namespace TinyBlogTest\Article;

use TinyBlog\Article\Content;

/**
 * @small
 */
class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $content = new Content();

        $this->assertEquals('', $content->getSource());
        $this->assertEquals('', $content->getHtml());
    }

    public function testGetters()
    {
        $content = new Content('**test** foo __bar__', '<b>test</b> foo <u>bar</u>');

        $this->assertEquals('**test** foo __bar__', $content->source());
        $this->assertEquals('**test** foo __bar__', $content->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $content->html());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $content->getHtml());
    }
}
