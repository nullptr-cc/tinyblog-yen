<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\SignInData;
use TinyBlog\Type\User;

class SigninHandler extends AjaxHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $data = SignInData::createFromRequest($request);
        $authenticator = $this->web->getUserAuthenticator();

        if ($authenticator->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $this->forbidden('Already signed in');
        };

        try {
            $user = $authenticator->authenticate($data->username(), $data->password());
        } catch (\InvalidArgumentException $ex) {
            return $this->badParams(['msg' => 'Invalid credentials']);
        };

        $this->web->getSession()->start();
        $authenticator->setAuthUser($user);

        return $this->ok(['redirect_url' => $this->web->getUrlBuilder()->buildMainPageUrl()]);
    }
}
