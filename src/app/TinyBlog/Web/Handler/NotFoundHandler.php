<?php

namespace TinyBlog\Web\Handler;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\BaseHandler;

class NotFoundHandler extends BaseHandler
{
    protected function getResponder()
    {
        return $this->modules->web()->getHtmlResponder();
    }

    protected function handleRequest(IServerRequest $request)
    {
        return $this->getResponder()->notFound('page not found');
    }
}
