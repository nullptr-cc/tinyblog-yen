<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\User\User;

class SignoutHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() == User::ROLE_NONE) {
            return $this->forbidden('Not signed in');
        };

        $this->modules->web()->getSession()->stop();

        return $this->redirect($this->modules->web()->getUrlBuilder()->buildMainPageUrl());
    }
}
