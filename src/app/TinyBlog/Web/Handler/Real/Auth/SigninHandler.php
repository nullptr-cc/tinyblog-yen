<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\RequestData\SignInData;
use TinyBlog\User\User;

class SigninHandler extends CommandHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        parent::checkAccess($request);

        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            throw new AccessDenied('Already signed in');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $authenticator = $this->modules->web()->getUserAuthenticator();

        $data = SignInData::createFromRequest($request);

        if (!$authenticator->authenticate($data->getUsername(), $data->getPassword())) {
            return $this->getResponder()->badParams(['msg' => 'Invalid credentials']);
        };

        $user = $this->modules->user()->getUserRepo()->getByUsername($data->getUsername());
        $this->modules->web()->getSession()->start();
        $authenticator->setAuthUser($user);

        return $this->getResponder()->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
