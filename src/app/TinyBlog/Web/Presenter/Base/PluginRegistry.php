<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Util\Contract\IClassResolver;
use TinyBlog\Web\ModuleWeb;

class PluginRegistry extends \Yen\Util\PluginRegistry
{
    protected $web;

    public function __construct(ModuleWeb $web, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->web = $web;
    }

    protected function make($name)
    {
        return parent::make($this->snakeToCamel($name));
    }

    protected function createExistent($classname)
    {
        return new $classname($this->web);
    }

    protected function snakeToCamel($string)
    {
        return implode(array_map('ucfirst', explode('_', $string)));
    }
}
