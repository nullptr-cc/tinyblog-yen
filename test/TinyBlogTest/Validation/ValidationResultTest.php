<?php

namespace TinyBlogTest\Validation;

use TinyBlog\Validation\ValidationResult;

class ValidationResultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $result = new ValidationResult(true, []);

        $this->assertTrue($result->valid());
        $this->assertCount(0, $result->getErrors());

        $result = new ValidationResult(false, ['foo' => 'bar']);

        $this->assertFalse($result->valid());
        $this->assertEquals(['foo' => 'bar'], $result->getErrors());
    }
}
