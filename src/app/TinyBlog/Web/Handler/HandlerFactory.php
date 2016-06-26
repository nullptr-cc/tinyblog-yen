<?php

namespace TinyBlog\Web\Handler;

use Yen\Handler\HandlerFactory as YenHandlerFactory;
use Yen\ClassResolver\Contract\IClassResolver;
use TinyBlog\Modules;

class HandlerFactory extends YenHandlerFactory
{
    private $modules;

    public function __construct(IClassResolver $resolver, Modules $modules)
    {
        parent::__construct($resolver);
        $this->modules = $modules;
    }

    protected function make($classname)
    {
        return new $classname($this->modules);
    }
}
