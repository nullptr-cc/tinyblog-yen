<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IResponse;
use TinyBlog\Core\Contract\IDependencyContainer;

abstract class BaseHandler extends \Yen\Handler\Handler
{
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

    protected function beforeHandle(IServerRequest $request)
    {
        $this->session->resume($request);
    }

    protected function afterHandle(IServerRequest $request, IResponse $response)
    {
        $this->session->suspend();
    }

    protected function getPresenter()
    {
        return $this->presenter;
    }

    protected function getErrorPresenter()
    {
        return $this->presenter;
    }

    protected function getAuthUser()
    {
        return $this->authenticator->getAuthUser();
    }
}
