<?php

namespace TinyBlog\Web\Handler\Real\Auth\Github;

use TinyBlog\Web\Handler\Base\Auth\BackHandler as BaseBackHandler;

class BackHandler extends BaseBackHandler
{
    protected function getProvider()
    {
        return $this->domain->getOAuthProviderGithub();
    }
}
