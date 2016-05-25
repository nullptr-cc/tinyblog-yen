<?php

namespace TinyBlogTest\OAuth;

use Yen\Settings\Contract\ISettings;
use Yen\HttpClient\Contract\IHttpClient;
use Yada\Driver as SqlDriver;
use TinyBlog\Tools\ModuleTools;
use TinyBlog\OAuth\ModuleOAuth;
use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\ProviderGoogle;
use TinyBlog\OAuth\OAuthUserRepo;

class ModuleOAuthTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $sql_driver = $this->prophesize(SqlDriver::class);
        $settings = $this->prophesize(ISettings::class);
        $tools = $this->prophesize(ModuleTools::class);

        $settings->get('github')->willReturn($this->prophesize(ISettings::class)->reveal());
        $settings->get('google')->willReturn($this->prophesize(ISettings::class)->reveal());
        $tools->getHttpClient()->willReturn($this->prophesize(IHttpClient::class)->reveal());

        $oauth = new ModuleOAuth($sql_driver->reveal(), $settings->reveal(), $tools->reveal());

        $this->assertInstanceOf(ProviderGithub::class, $oauth->getProviderGithub());
        $this->assertInstanceOf(ProviderGoogle::class, $oauth->getProviderGoogle());
        $this->assertInstanceOf(OAuthUserRepo::class, $oauth->getOAuthUserRepo());
    }
}
