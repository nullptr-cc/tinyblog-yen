<?php

namespace TinyBlog\Web\Handler;

use Yen\Handler\Contract\IHandler;
use TinyBlog\Modules;

abstract class BaseHandler implements IHandler
{
    protected $modules;

    public function __construct(Modules $modules)
    {
        $this->modules = $modules;
    }

    protected function getAuthUser()
    {
        return $this->modules->web()->getUserAuthenticator()->getAuthUser();
    }
}
