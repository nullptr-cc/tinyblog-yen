<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;

class SignoutHandler extends BaseHandler
{
    public function onPost(IServerRequest $request)
    {
        if (!$this->authenticator->getAuthUser()) {
            return $this->forbidden('Not signed in');
        };

        $this->session->stop();

        return $this->redirect($this->url_builder->buildMainPageUrl());
    }
}
