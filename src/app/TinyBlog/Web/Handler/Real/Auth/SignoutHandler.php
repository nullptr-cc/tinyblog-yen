<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\User\User;

class SignoutHandler extends Handler
{
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

        if ($this->getAuthUser()->getRole() == User::ROLE_NONE) {
            return $responder->forbidden('Not signed in');
        };

        $this->modules->web()->getSession()->stop();

        return $responder->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
