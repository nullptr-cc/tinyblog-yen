<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Handler\Contract\IHandler;
use TinyBlog\Modules;

abstract class Handler implements IHandler
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
