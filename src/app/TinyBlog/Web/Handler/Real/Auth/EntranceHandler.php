<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;

class EntranceHandler extends BaseHandler
{
    public function onGet(IServerRequest $request)
    {
        if ($this->authenticator->getAuthUser()) {
            return $this->forbidden('Already signed in');
        };

        return $this->ok('Page/Auth/Entrance');
    }
}
