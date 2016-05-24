<?php

namespace TinyBlogTest\User;

use TinyBlog\User\UserRepo;
use TinyBlog\User\DataAccess\UserStore;
use TinyBlog\User\User;
use TinyBlog\User\EUserNotExists;

class UserRepoTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $store = $this->prophesize(UserStore::class);
        $user = new User(['username' => 'foobar']);

        $store->insert($user)->willReturn((object)['id' => 42]);

        $repo = new UserRepo($store->reveal());
        $saved = $repo->persist($user);

        $this->assertNotSame($saved, $user);
        $this->assertEquals(42, $saved->getId());
        $this->assertEquals('foobar', $saved->getUsername());
    }

    public function testUserExists()
    {
        $store = $this->prophesize(UserStore::class);

        $store->countById(42)->willReturn(1);

        $repo = new UserRepo($store->reveal());

        $this->assertTrue($repo->userExists(42));
    }

    public function testUsernameExists()
    {
        $store = $this->prophesize(UserStore::class);

        $store->countByUsername('foobar')->willReturn(0);

        $repo = new UserRepo($store->reveal());

        $this->assertFalse($repo->usernameExists('foobar'));
    }

    public function testGetById()
    {
        $store = $this->prophesize(UserStore::class);

        $store->fetchById(42)->willReturn([new User(['id' => 42, 'username' => 'foobar'])]);

        $repo = new UserRepo($store->reveal());
        $user = $repo->getById(42);

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('foobar', $user->getUsername());
    }

    public function testGetByIdException()
    {
        $this->expectException(EUserNotExists::class);

        $store = $this->prophesize(UserStore::class);

        $store->fetchById(42)->willReturn([]);

        $repo = new UserRepo($store->reveal());
        $user = $repo->getById(42);
    }

    public function testGetByUsername()
    {
        $store = $this->prophesize(UserStore::class);

        $store->fetchByUsername('foobar')->willReturn([new User(['id' => 42, 'username' => 'foobar'])]);

        $repo = new UserRepo($store->reveal());
        $user = $repo->getByUsername('foobar');

        $this->assertEquals(42, $user->getId());
        $this->assertEquals('foobar', $user->getUsername());
    }

    public function testGetByUsernameException()
    {
        $this->expectException(EUserNotExists::class);

        $store = $this->prophesize(UserStore::class);

        $store->fetchByUsername('foobar')->willReturn([]);

        $repo = new UserRepo($store->reveal());
        $user = $repo->getByUsername('foobar');
    }
}
