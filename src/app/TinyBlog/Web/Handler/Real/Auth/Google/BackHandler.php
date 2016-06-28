<?php

namespace TinyBlog\Web\Handler\Real\Auth\Google;

use TinyBlog\Web\Handler\Base\Auth\OAuthBackHandler;

class BackHandler extends OAuthBackHandler
{
    protected function getProvider()
    {
        return $this->modules->oauth()->getProviderGoogle();
    }
}
