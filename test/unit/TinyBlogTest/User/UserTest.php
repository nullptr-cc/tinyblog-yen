<?php

namespace TinyBlogTest\User;

use TinyBlog\User\User;
use TinyBlog\User\UserRole;

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
        $this->assertEquals(UserRole::GUEST, $user->getRole()->value());
    }

    public function testGetters()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $this->assertEquals(42, $user->id());
        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->nickname());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->username());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->password());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->role()->value());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }

    public function testWithId()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $clone = $user->withId(999);

        $this->assertEquals(999, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $clone->getRole()->value());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }

    public function testWithNickname()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $clone = $user->withNickname('John Doe');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('John Doe', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $clone->getRole()->value());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }

    public function testWithUsername()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $clone = $user->withUsername('johndoe');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('johndoe', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $clone->getRole()->value());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }

    public function testWithPassword()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $clone = $user->withPassword('$ecRet');

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('$ecRet', $clone->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $clone->getRole()->value());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }

    public function testWithRole()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'pas$WoRd',
            'role' => UserRole::CONSUMER()
        ]);

        $clone = $user->withRole(UserRole::AUTHOR());

        $this->assertEquals(42, $clone->getId());
        $this->assertEquals('Foo Bar', $clone->getNickname());
        $this->assertEquals('foobar', $clone->getUsername());
        $this->assertEquals('pas$WoRd', $clone->getPassword());
        $this->assertEquals(UserRole::AUTHOR, $clone->getRole()->value());

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('Foo Bar', $user->getNickname());
        $this->assertEquals('foobar', $user->getUsername());
        $this->assertEquals('pas$WoRd', $user->getPassword());
        $this->assertEquals(UserRole::CONSUMER, $user->getRole()->value());
    }
}
