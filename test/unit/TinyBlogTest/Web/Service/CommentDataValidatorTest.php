<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Web\Service\CommentDataValidator;
use TinyBlog\Web\RequestData\CommentData;

class CommentDataValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateSuccess()
    {
        $data = new CommentData(42, 'Test comment body');
        $validator = new CommentDataValidator();
        $result = $validator->validate($data);

        $this->assertTrue($result->valid());
        $this->assertCount(0, $result->getErrors());
    }

    public function testValidateErrors()
    {
        $data = new CommentData(42, '');
        $validator = new CommentDataValidator();
        $result = $validator->validate($data);

        $this->assertFalse($result->valid());
        $this->assertEquals(['body' => 'empty body'], $result->getErrors());

    }
}
