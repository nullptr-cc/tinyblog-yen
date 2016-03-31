<?php

namespace TinyBlog\Web\Handler\Real\Auth\Github;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;

class BeginHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        return $this->redirect(
            $this->domain->getOAuthProviderGithub()->getAuthUrl()
        );
    }
}
