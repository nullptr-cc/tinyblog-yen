<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\OAuthUser;
use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\ProviderGoogle;
use TinyBlog\User\User;

/**
 * @small
 */
class OAuthUserTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $oa_user = new OAuthUser();

        $this->assertNull($oa_user->getUser());
        $this->assertNull($oa_user->getProvider());
        $this->assertNull($oa_user->getIdentifier());
    }

    public function testGetters()
    {
        $oa_user = $this->prepareOAuthUser();

        $this->assertEquals(ProviderGithub::ID, $oa_user->getProvider());
        $this->assertEquals(5209711, $oa_user->getIdentifier());
        $this->assertInstanceOf(User::class, $oa_user->getUser());
        $this->assertEquals(42, $oa_user->getUser()->getId());
    }

    public function testWithUser()
    {
        $oa_user = $this->prepareOAuthUser();

        $clone = $oa_user->withUser(new User(['id' => 99]));

        $this->assertEquals(ProviderGithub::ID, $clone->getProvider());
        $this->assertEquals(5209711, $clone->getIdentifier());
        $this->assertInstanceOf(User::class, $clone->getUser());
        $this->assertEquals(99, $clone->getUser()->getId());

        $this->assertEquals(ProviderGithub::ID, $oa_user->getProvider());
        $this->assertEquals(5209711, $oa_user->getIdentifier());
        $this->assertInstanceOf(User::class, $oa_user->getUser());
        $this->assertEquals(42, $oa_user->getUser()->getId());
    }

    public function testWithProvider()
    {
        $oa_user = $this->prepareOAuthUser();

        $clone = $oa_user->withProvider(ProviderGoogle::ID);

        $this->assertEquals(ProviderGoogle::ID, $clone->getProvider());
        $this->assertEquals(5209711, $clone->getIdentifier());
        $this->assertInstanceOf(User::class, $clone->getUser());
        $this->assertEquals(42, $clone->getUser()->getId());

        $this->assertEquals(ProviderGithub::ID, $oa_user->getProvider());
        $this->assertEquals(5209711, $oa_user->getIdentifier());
        $this->assertInstanceOf(User::class, $oa_user->getUser());
        $this->assertEquals(42, $oa_user->getUser()->getId());
    }

    public function testWithIdentifier()
    {
        $oa_user = $this->prepareOAuthUser();

        $clone = $oa_user->withIdentifier(9978845);

        $this->assertEquals(ProviderGithub::ID, $clone->getProvider());
        $this->assertEquals(9978845, $clone->getIdentifier());
        $this->assertInstanceOf(User::class, $clone->getUser());
        $this->assertEquals(42, $clone->getUser()->getId());

        $this->assertEquals(ProviderGithub::ID, $oa_user->getProvider());
        $this->assertEquals(5209711, $oa_user->getIdentifier());
        $this->assertInstanceOf(User::class, $oa_user->getUser());
        $this->assertEquals(42, $oa_user->getUser()->getId());
    }

    private function prepareOAuthUser()
    {
        $user = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar'
        ]);

        $oa_user = new OAuthUser([
            'user' => $user,
            'provider' => ProviderGithub::ID,
            'identifier' => 5209711
        ]);

        return $oa_user;
    }

}
