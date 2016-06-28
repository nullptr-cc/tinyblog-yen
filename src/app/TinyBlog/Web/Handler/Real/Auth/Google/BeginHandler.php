<?php

namespace TinyBlog\Web\Handler\Real\Auth\Google;

use TinyBlog\Web\Handler\Base\Auth\OAuthBeginHandler;

class BeginHandler extends OAuthBeginHandler
{
    protected function getProvider()
    {
        return $this->modules->oauth()->getProviderGoogle();
    }
}
