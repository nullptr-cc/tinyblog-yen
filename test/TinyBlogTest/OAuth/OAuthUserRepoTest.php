<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\OAuthUserRepo;
use TinyBlog\OAuth\OAuthUser;
use TinyBlog\OAuth\DataAccess\OAuthUserStore;
use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\ProviderGoogle;

class OAuthUserRepoTest extends \PHPUnit_Framework_TestCase
{
    public function testFind()
    {
        $store = $this->prophesize(OAuthUserStore::class);
        $store->fetch(['provider' => ProviderGithub::ID, 'identifier' => 1234])
              ->willReturn([new OAuthUser(['provider' => ProviderGithub::ID, 'identifier' => 1234])]);

        $repo = new OAuthUserRepo($store->reveal());
        $result = $repo->find(ProviderGithub::ID, 1234);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(OAuthUser::class, $result[0]);
        $this->assertEquals(ProviderGithub::ID, $result[0]->getProvider());
        $this->assertEquals(1234, $result[0]->getIdentifier());
    }

    public function testFindEmpty()
    {
        $store = $this->prophesize(OAuthUserStore::class);
        $store->fetch(['provider' => ProviderGithub::ID, 'identifier' => 1234567])
              ->willReturn([]);

        $repo = new OAuthUserRepo($store->reveal());
        $result = $repo->find(ProviderGithub::ID, 1234567);

        $this->assertCount(0, $result);
    }

    public function testPersist()
    {
        $oauser = new OAuthUser(['provider' => ProviderGithub::ID, 'identifier' => 1234]);

        $store = $this->prophesize(OAuthUserStore::class);
        $store->insert($oauser)->willReturn(true);

        $repo = new OAuthUserRepo($store->reveal());
        $result = $repo->persist($oauser);

        $this->assertSame($repo, $result);
    }
}
