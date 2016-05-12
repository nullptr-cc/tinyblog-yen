<?php

namespace TinyBlog\OAuth;

use Yen\Settings\Contract\ISettings;
use Yada\Driver;
use TinyBlog\Tools\ModuleTools;

class ModuleOAuth
{
    private $sql_driver;
    private $settings;
    private $tools;

    public function __construct(
        Driver $sql_driver,
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
        return new DataAccess\OAuthUserStore($this->sql_driver);
    }
}
