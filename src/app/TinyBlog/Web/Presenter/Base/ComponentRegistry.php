<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Util\Contract\IClassResolver;
use TinyBlog\Core\Contract\IDependencyContainer;

class ComponentRegistry extends \Yen\Presenter\ComponentRegistry
{
    protected $dc;

    public function __construct(IDependencyContainer $dc, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->dc = $dc;
    }

    protected function createExistent($classname)
    {
        return new $classname($this->dc, $this);
    }
}
