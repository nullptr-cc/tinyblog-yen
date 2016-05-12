<?php

namespace TinyBlog\Web\Handler\Real\Auth\Google;

use TinyBlog\Web\Handler\Base\Auth\BackHandler as BaseBackHandler;

class BackHandler extends BaseBackHandler
{
    protected function getProvider()
    {
        return $this->modules->oauth()->getProviderGoogle();
    }
}
