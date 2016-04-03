<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Type\User;

abstract class BeginHandler extends CommonHandler
{
    abstract protected function getProvider();

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $this->forbidden('Already signed in');
        };

        return $this->redirect(
            $this->getProvider()->getAuthUrl()
        );
    }
}
