<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\SignInData;
use TinyBlog\User\User;

class SigninHandler extends AjaxHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $this->forbidden('Blocked');
        };

        $authenticator = $this->modules->web()->getUserAuthenticator();

        if ($authenticator->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $this->forbidden('Already signed in');
        };

        $data = SignInData::createFromRequest($request);

        if (!$authenticator->authenticate($data->getUsername(), $data->getPassword())) {
            return $this->badParams(['msg' => 'Invalid credentials']);
        };

        $user = $this->modules->user()->getUserRepo()->getByUsername($data->getUsername());
        $this->modules->web()->getSession()->start();
        $authenticator->setAuthUser($user);

        return $this->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
