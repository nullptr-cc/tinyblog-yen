<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Handler\Contract\IHandler;
use Yen\Handler\HandlerResponseHelpers;
use TinyBlog\Core\Contract\IDependencyContainer;

abstract class Handler implements IHandler
{
    use HandlerResponseHelpers;

    protected $presenter;
    protected $url_builder;
    protected $session;
    protected $authenticator;
    protected $domain_registry;

    public function __construct(IDependencyContainer $dc)
    {
        $this->presenter = $dc->getHtmlPresenter();
        $this->url_builder = $dc->getTools()->getUrlBuilder();
        $this->session = $dc->getSession();
        $this->authenticator = $dc->getUserAuthenticator();
        $this->domain_registry = $dc->getDomainRegistry();
    }

    protected function getAuthUser()
    {
        return $this->authenticator->getAuthUser();
    }
}
