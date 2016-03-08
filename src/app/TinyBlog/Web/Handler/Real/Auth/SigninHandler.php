<?php

namespace TinyBlog\Web\Handler\Real\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\SignInData;

class SigninHandler extends AjaxHandler
{
    public function onPost(IServerRequest $request)
    {
        $data = SignInData::createFromRequest($request);

        if ($this->authenticator->getAuthUser()) {
            return $this->forbidden('Already signed in');
        };

        try {
            $user = $this->authenticator->authenticate($data->username(), $data->password());
        } catch (\InvalidArgumentException $ex) {
            return $this->badParams(['msg' => 'Invalid credentials']);
        };

        $this->session->start();
        $this->authenticator->setAuthUser($user);

        return $this->ok(['redirect_url' => $this->url_builder->buildMainPageUrl()]);
    }
}
