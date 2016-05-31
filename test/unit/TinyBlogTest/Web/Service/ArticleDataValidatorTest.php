<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Web\Service\ArticleDataValidator;
use TinyBlog\Web\RequestData\ArticleData;

class ArticleDataValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateSuccess()
    {
        $data = new ArticleData(42, 'Title', 'Body');
        $validator = new ArticleDataValidator();
        $result = $validator->validate($data);

        $this->assertTrue($result->valid());
        $this->assertCount(0, $result->getErrors());
    }

    public function testValidateErrors()
    {
        $data = new ArticleData(42, '', '  ');
        $validator = new ArticleDataValidator();
        $result = $validator->validate($data);

        $this->assertFalse($result->valid());
        $this->assertEquals(
            ['title' => 'empty title', 'body' => 'empty body'],
            $result->getErrors()
        );
    }
}
