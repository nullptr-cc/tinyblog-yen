<?php

namespace TinyBlog\Web\Handler;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Exception\MethodNotAllowed;
use TinyBlog\Web\Handler\Exception\UntrustedRequest;

abstract class CommandHandler extends BaseHandler
{
    protected function getResponder()
    {
        return $this->modules->web()->getJsonResponder();
    }

    protected function checkRequest(IServerRequest $request)
    {
        if ($request->getMethod() !== IRequest::METHOD_POST) {
            throw new MethodNotAllowed();
        };

        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            throw new UntrustedRequest();
        };
    }
}
