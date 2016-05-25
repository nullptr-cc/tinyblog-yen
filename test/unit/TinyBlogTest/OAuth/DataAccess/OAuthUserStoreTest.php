<?php

namespace TinyBlogTest\OAuth\DataAccess;

use TinyBlog\OAuth\DataAccess\OAuthUserStore;
use TinyBlog\OAuth\OAuthUser;
use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\ProviderGoogle;
use TinyBlog\User\User;

class OAuthUserStoreTest extends \TestExt\DatabaseTestCase
{
    public function testInsert()
    {
        $store = new OAuthUserStore($this->getSqlDriver());
        $oauser = $this->prepareOAuthUser();
        $result = $store->insert($oauser);

        $this->assertSame($store, $result);
        $this->assertEquals(2, $this->getConnection()->getRowCount('oauth_user'));

        $query_ds =
            $this->getConnection()
                 ->createQueryTable('oauth_user', 'select * from oauth_user where user_id = 1 and provider = 1');
        $expect_ds =
            $this->createMySQLXMLDataSet(DBFIXT_PATH . '/oauth-user-inserted.xml')
                 ->getTable('oauth_user');
        $this->assertTablesEqual($expect_ds, $query_ds);
    }

    public function testFetch()
    {
        $store = new OAuthUserStore($this->getSqlDriver());

        $result = $store->fetch();

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(OAuthUser::class, $result);
        $this->assertInstanceOf(User::class, $result[0]->getUser());
        $this->assertEquals(1, $result[0]->getUser()->getId());
        $this->assertEquals(ProviderGoogle::ID, $result[0]->getProvider());
        $this->assertEquals(2343215346, $result[0]->getIdentifier());

        $result = $store->fetch(['user_id' => 1, 'provider' => ProviderGoogle::ID]);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(OAuthUser::class, $result);
        $this->assertInstanceOf(User::class, $result[0]->getUser());
        $this->assertEquals(1, $result[0]->getUser()->getId());
        $this->assertEquals(ProviderGoogle::ID, $result[0]->getProvider());
        $this->assertEquals(2343215346, $result[0]->getIdentifier());

        $result = $store->fetch(['user_id' => 1, 'provider' => ProviderGithub::ID]);
        $this->assertCount(0, $result);

        $result = $store->fetch(['provider' => ProviderGithub::ID, 'identifier' => 12416]);
        $this->assertCount(0, $result);

        $result = $store->fetch(['provider' => ProviderGithub::ID, 'test-default-case' => 'xxx']);
        $this->assertCount(0, $result);
    }

    protected function prepareOAuthUser()
    {
        $user = new User(['id' => 1]);
        $oauser = new OAuthUser([
            'user' => $user,
            'provider' => ProviderGithub::ID,
            'identifier' => 19035
        ]);

        return $oauser;
    }
}
