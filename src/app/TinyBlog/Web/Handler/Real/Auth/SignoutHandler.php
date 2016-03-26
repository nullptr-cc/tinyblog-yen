<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;

class SignoutHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        if (!$this->getAuthUser()) {
            return $this->forbidden('Not signed in');
        };

        $this->web->getSession()->stop();

        return $this->redirect($this->web->getUrlBuilder()->buildMainPageUrl());
    }
}
