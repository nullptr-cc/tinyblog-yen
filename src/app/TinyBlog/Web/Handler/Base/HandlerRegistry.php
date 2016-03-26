<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Util\Contract\IClassResolver;
use Yen\Handler\HandlerRegistry as YenHandlerRegistry;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Domain\DomainRegistry;

class HandlerRegistry extends YenHandlerRegistry
{
    protected $web;
    protected $domain;

    public function __construct(WebRegistry $web, DomainRegistry $domain, IClassResolver $resolver)
    {
        parent::__construct($resolver);
        $this->web = $web;
        $this->domain = $domain;
    }

    protected function createExistent($classname)
    {
        return new $classname($this->web, $this->domain);
    }
}
