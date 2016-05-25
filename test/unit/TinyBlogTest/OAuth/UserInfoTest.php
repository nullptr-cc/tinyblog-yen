<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\UserInfo;

class UserInfoTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $user_info = new UserInfo(765, 'foobar', 'foo@bar.net');

        $this->assertEquals(765, $user_info->identifier());
        $this->assertEquals('foobar', $user_info->name());
        $this->assertEquals('foo@bar.net', $user_info->email());
    }
}
