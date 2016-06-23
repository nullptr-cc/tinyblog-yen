<?php

namespace TinyBlog\Web\Handler;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;

class MissedHandler extends Handler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        return $this->modules->web()->getHtmlResponder()->notFound('page not found');
    }
}
