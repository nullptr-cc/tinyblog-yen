<?php

namespace TinyBlog\Web\Handler\Real\Auth\Github;

use TinyBlog\Web\Handler\Base\Auth\OAuthBeginHandler;

class BeginHandler extends OAuthBeginHandler
{
    protected function getProvider()
    {
        return $this->modules->oauth()->getProviderGithub();
    }
}
