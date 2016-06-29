<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;

class SignoutHandler extends CommandHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        parent::checkAccess($request);

        if ($this->getAuthUser()->isGuest()) {
            throw new AccessDenied('Not signed in');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $this->modules->web()->getSession()->stop();

        return $this->getResponder()->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
