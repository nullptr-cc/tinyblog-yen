<?php

namespace TinyBlog\OAuth;

use Yada\Driver as SqlDriver;
use Yen\Settings\Contract\ISettings;
use TinyBlog\Tools\ModuleTools;
use TinyBlog\OAuth\DataAccess\OAuthUserStore;

class ModuleOAuth
{
    private $sql_driver;
    private $settings;
    private $tools;

    public function __construct(
        SqlDriver $sql_driver,
        ISettings $settings,
        ModuleTools $tools
    ) {
        $this->sql_driver = $sql_driver;
        $this->settings = $settings;
        $this->tools = $tools;
    }

    public function getProviderGithub()
    {
        return new ProviderGithub(
            $this->settings->get('github'),
            $this->tools->getHttpClient()
        );
    }

    public function getProviderGoogle()
    {
        return new ProviderGoogle(
            $this->settings->get('google'),
            $this->tools->getHttpClient()
        );
    }

    public function getOAuthUserRepo()
    {
        return new OAuthUserRepo($this->getOAuthUserStore());
    }

    private function getOAuthUserStore()
    {
        return new OAuthUserStore($this->sql_driver);
    }
}
