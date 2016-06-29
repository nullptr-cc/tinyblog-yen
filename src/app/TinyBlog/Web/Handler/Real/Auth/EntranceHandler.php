<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;

class EntranceHandler extends QueryHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->isNotGuest()) {
            throw new AccessDenied('Already signed in');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        return $this->getResponder()->ok('Page/Auth/Entrance');
    }
}
