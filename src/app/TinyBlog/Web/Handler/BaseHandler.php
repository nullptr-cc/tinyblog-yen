<?php

namespace TinyBlog\Web\Handler;

use Yen\Handler\Contract\IHandler;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Modules;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\Handler\Exception\UntrustedRequest;
use TinyBlog\Web\Handler\Exception\MethodNotAllowed;

abstract class BaseHandler implements IHandler
{
    protected $modules;

    public function __construct(Modules $modules)
    {
        $this->modules = $modules;
    }

    protected function getAuthUser()
    {
        return $this->modules->web()->getUserAuthenticator()->getAuthUser();
    }

    public function handle(IServerRequest $request)
    {
        try {
            $this->checkRequest($request);
            $this->checkAccess($request);
        } catch (MethodNotAllowed $ex) {
            return $this->getResponder()->badRequest();
        } catch (UntrustedRequest $ex) {
            return $this->getResponder()->badRequest();
        } catch (AccessDenied $ex) {
            return $this->getResponder()->forbidden($ex->getMessage());
        };

        return $this->handleRequest($request);
    }

    protected function checkRequest(IServerRequest $request)
    {
    }

    protected function checkAccess(IServerRequest $request)
    {
    }

    abstract protected function handleRequest(IServerRequest $request);

    abstract protected function getResponder();
}
