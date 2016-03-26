<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Handler\Contract\IHandler;
use Yen\Handler\HandlerResponseHelpers;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Domain\DomainRegistry;

abstract class Handler implements IHandler
{
    use HandlerResponseHelpers;

    protected $web;
    protected $domain;

    public function __construct(WebRegistry $web, DomainRegistry $domain)
    {
        $this->web = $web;
        $this->domain = $domain;
    }

    protected function getAuthUser()
    {
        return $this->web->getUserAuthenticator()->getAuthUser();
    }
}
