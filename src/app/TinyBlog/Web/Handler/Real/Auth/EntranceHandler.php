<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\User\User;

class EntranceHandler extends Handler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getHtmlResponder();

        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $responder->forbidden('Already signed in');
        };

        return $responder->ok('Page/Auth/Entrance');
    }
}
