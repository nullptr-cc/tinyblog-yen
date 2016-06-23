<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\Web\RequestData\SignInData;
use TinyBlog\User\User;

class SigninHandler extends Handler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getJsonResponder();

        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $responder->forbidden('Blocked');
        };

        $authenticator = $this->modules->web()->getUserAuthenticator();

        if ($authenticator->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $responder->forbidden('Already signed in');
        };

        $data = SignInData::createFromRequest($request);

        if (!$authenticator->authenticate($data->getUsername(), $data->getPassword())) {
            return $responder->badParams(['msg' => 'Invalid credentials']);
        };

        $user = $this->modules->user()->getUserRepo()->getByUsername($data->getUsername());
        $this->modules->web()->getSession()->start();
        $authenticator->setAuthUser($user);

        return $responder->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
