<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Util\Contract\IClassResolver;
use TinyBlog\Core\Contract\IDependencyContainer;

class HandlerRegistry extends \Yen\Handler\HandlerRegistry
{
    protected $deps;

    public function __construct(IDependencyContainer $deps, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->deps = $deps;
    }

    protected function createExistent($classname)
    {
        return new $classname($this->deps);
    }
}
