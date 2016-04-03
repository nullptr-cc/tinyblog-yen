<?php

namespace TinyBlog\Web\Handler\Real\Auth\Google;

use TinyBlog\Web\Handler\Base\Auth\BeginHandler as BaseBeginHandler;

class BeginHandler extends BaseBeginHandler
{
    protected function getProvider()
    {
        return $this->domain->getOAuthProviderGoogle();
    }
}
