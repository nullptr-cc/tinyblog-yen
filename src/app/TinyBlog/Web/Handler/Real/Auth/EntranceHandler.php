<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;

class EntranceHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        if ($this->authenticator->getAuthUser()) {
            return $this->forbidden('Already signed in');
        };

        return $this->ok('Page/Auth/Entrance');
    }
}
