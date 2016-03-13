<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Util\Contract\IClassResolver;
use TinyBlog\Core\Contract\IDependencyContainer;

class ComponentRegistry extends \Yen\Util\CommonRegistry implements IComponents
{
    protected $dc;

    public function __construct(IDependencyContainer $dc, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->dc = $dc;
    }

    public function getComponent($name)
    {
        return $this->get($name);
    }

    protected function createExistent($classname)
    {
        return new $classname($this->dc, $this);
    }
}
