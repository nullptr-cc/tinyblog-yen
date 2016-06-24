<?php

namespace TinyBlog\Web\Handler;

use Yen\Handler\HandlerFactory as YenHandlerFactory;

class HandlerFactory extends YenHandlerFactory
{
    private $modules;

    public function __construct($resolver, $modules)
    {
        parent::__construct($resolver);
        $this->modules = $modules;
    }

    protected function make($classname)
    {
        return new $classname($this->modules);
    }
}
