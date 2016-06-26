<?php

namespace TinyBlog\Web\Handler;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Exception\MethodNotAllowed;

abstract class QueryHandler extends BaseHandler
{
    protected function getResponder()
    {
        return $this->modules->web()->getHtmlResponder();
    }

    protected function checkRequest(IServerRequest $request)
    {
        if ($request->getMethod() !== IRequest::METHOD_GET) {
            throw new MethodNotAllowed();
        };
    }
}
