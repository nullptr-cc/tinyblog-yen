<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\Handler\HandlerRegistry as YenHandlerRegistry;
use TinyBlog\Modules;

class HandlerRegistry extends YenHandlerRegistry
{
    protected $modules;

    public function __construct(Modules $modules, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->modules = $modules;
    }

    protected function createExistent($classname)
    {
        return new $classname($this->modules);
    }
}
