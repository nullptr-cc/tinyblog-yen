<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\User\User;

abstract class BeginHandler extends AjaxHandler
{
    abstract protected function getProvider();

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $this->forbidden('Blocked');
        };

        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $this->forbidden('Already signed in');
        };

        return $this->ok(['redirect_url' => $this->getProvider()->getAuthUrl()]);
    }
}
