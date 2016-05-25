<?php

namespace TinyBlogTest\User;

use TinyBlog\User\User;

/**
 * @small
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $user = new User();

        $this->assertNull($user->getId());
        $this->assertNull($user->getNickname());
        $this->assertNull($user->getUsername());
        $this->assertNull($user->getPassword());
        $this->assertEquals(User::ROLE_NONE, $user->getRole());
    }

    public function testGetters()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $this->assertEquals(42, $user->id());
        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->nickname());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->username());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->password());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->role());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }

    public function testWithId()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $clone = $user->withId(999);

        $this->assertEquals(999, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $clone->getRole());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }

    public function testWithNickname()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $clone = $user->withNickname('John Doe');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('John Doe', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $clone->getRole());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }

    public function testWithUsername()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $clone = $user->withUsername('johndoe');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('johndoe', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $clone->getRole());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }

    public function testWithPassword()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $clone = $user->withPassword('$ecRet');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('$ecRet', $clone->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $clone->getRole());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }

    public function testWithRole()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => User::ROLE_CONSUMER
        ]);

        $clone = $user->withRole(User::ROLE_AUTHOR);

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(User::ROLE_AUTHOR, $clone->getRole());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(User::ROLE_CONSUMER, $user->getRole());
    }
}
