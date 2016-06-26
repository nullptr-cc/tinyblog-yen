<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\User\User;

class EntranceHandler extends QueryHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            throw new AccessDenied('Already signed in');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        return $this->getResponder()->ok('Page/Auth/Entrance');
    }
}
