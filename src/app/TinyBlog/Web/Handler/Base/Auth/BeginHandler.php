<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\User\User;

abstract class BeginHandler extends Handler
{
    abstract protected function getProvider();

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getJsonResponder();

        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $responder->forbidden('Blocked');
        };

        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $responder->forbidden('Already signed in');
        };

        return $responder->ok(['redirect_url' => $this->getProvider()->getAuthUrl()]);
    }
}
