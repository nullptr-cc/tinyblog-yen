<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\ClassResolver\Contract\IClassResolver;
use Yen\Util\CommonRegistry;
use TinyBlog\Web\ModuleWeb;

class ComponentRegistry extends CommonRegistry
{
    protected $web;

    public function __construct(ModuleWeb $web, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->web = $web;
    }

    public function getComponent($name)
    {
        return $this->get($name);
    }

    protected function createExistent($classname)
    {
        return new $classname($this->web, $this);
    }
}
