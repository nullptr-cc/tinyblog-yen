<?php

namespace TinyBlog\Web\Handler\Real\Auth\Github;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Type\User;

class BeginHandler extends CommonHandler
{
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
            $this->domain->getOAuthProviderGithub()->getAuthUrl()
        );
    }
}
