<?php

namespace TinyBlogTest\User\DataAccess;

use TinyBlog\User\DataAccess\UserStore;
use TinyBlog\User\User;

class UserStoreTest extends \TestExt\DatabaseTestCase
{
    public function testCountById()
    {
        $store = new UserStore($this->getSqlDriver());

        $this->assertEquals(1, $store->countById(1));
        $this->assertEquals(0, $store->countById(101));
    }

    public function testCountByUsername()
    {
        $store = new UserStore($this->getSqlDriver());

        $this->assertEquals(1, $store->countByUsername('demo'));
        $this->assertEquals(0, $store->countByUsername('foobar'));
    }

    public function testFetchById()
    {
        $store = new UserStore($this->getSqlDriver());
        $result = $store->fetchById(1);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(User::class, $result);
        $this->assertEquals(1, $result[0]->getId());
        $this->assertEquals('demo', $result[0]->getUsername());

        $this->assertCount(0, $store->fetchById(101));
    }

    public function testFetchByUsername()
    {
        $store = new UserStore($this->getSqlDriver());
        $result = $store->fetchByUsername('demo');

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(User::class, $result);
        $this->assertEquals(1, $result[0]->getId());
        $this->assertEquals('demo', $result[0]->getUsername());

        $this->assertCount(0, $store->fetchByUsername('foobar'));
    }

    public function testInsert()
    {
        $user = User::consumer([
            'nickname' => 'Foo Bar',
            'username' => 'foobar',
            'password' => 'xxx$yyy'
        ]);
        $store = new UserStore($this->getSqlDriver());
        $result = $store->insert($user);

        $this->assertEquals(2, $result->id);
        $this->assertEquals(2, $this->getConnection()->getRowCount('user'));

        $query_ds = $this->getConnection()->createQueryTable('user', 'select * from user where id = 2');
        $expect_ds = $this->createMySQLXMLDataSet(DBFIXT_PATH . '/user-inserted.xml')->getTable('user');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }
}
