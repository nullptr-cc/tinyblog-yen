<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Util\Contract\IClassResolver;
use TinyBlog\Core\Contract\IDependencyContainer;

class PluginRegistry extends \Yen\Util\PluginRegistry
{
    protected $deps;

    public function __construct(IDependencyContainer $deps, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->deps = $deps;
    }

    protected function make($name)
    {
        return parent::make($this->snakeToCamel($name));
    }

    protected function createExistent($classname)
    {
        return new $classname($this->deps);
    }

    protected function snakeToCamel($string)
    {
        return implode(array_map('ucfirst', explode('_', $string)));
    }
}
