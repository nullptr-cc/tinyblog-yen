<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;

abstract class OAuthBeginHandler extends CommandHandler
{
    abstract protected function getProvider();

    protected function checkAccess(IServerRequest $request)
    {
        parent::checkAccess($request);

        if ($this->getAuthUser()->isNotGuest()) {
            throw new AccessDenied('Already signed in');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        return $this->getResponder()->ok([
            'redirect_url' => $this->getProvider()->getAuthUrl()
        ]);
    }
}
