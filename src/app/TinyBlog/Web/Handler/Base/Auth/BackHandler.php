<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\BaseHandler;
use TinyBlog\User\User;
use TinyBlog\OAuth\OAuthUser;
use TinyBlog\OAuth\IProvider;
use TinyBlog\OAuth\UserInfo;

abstract class BackHandler extends BaseHandler
{
    abstract protected function getProvider();

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getHtmlResponder();

        if ($this->getAuthUser()->getRole() > User::ROLE_NONE) {
            return $responder->forbidden('Already signed in');
        };

        $provider = $this->getProvider();

        $code = $provider->grabAuthCode($request);
        if ($code == '') {
            return $responder->forbidden();
        };

        $token = $provider->getAccessToken($code);
        if ($token == '') {
            return $responder->forbidden();
        };

        $info = $provider->getUserInfo($token);
        if ($info->identifier() == 0) {
            return $responder->error();
        };

        $repo = $this->modules->oauth()->getOAuthUserRepo();
        $res = $repo->find($provider->getId(), $info->identifier());

        if (!count($res)) {
            $user = $this->createUser($provider, $info);
        } else {
            $user = $res[0]->getUser();
        };

        $this->modules->web()->getSession()->start();
        $this->modules->web()->getUserAuthenticator()->setAuthUser($user);

        $redirector = $this->modules->web()->getRedirectResponder();

        return $redirector->redirect($this->modules->web()->getUrlBuilder()->buildMainPageUrl());
    }

    protected function createUser(IProvider $provider, UserInfo $info)
    {
        $user = new User([
            'username' => sprintf('oauth:%d:%s', $provider->getId(), $info->identifier()),
            'nickname' => $info->name(),
            'password' => 'xxx',
            'role'     => User::ROLE_CONSUMER
        ]);

        $user = $this->modules->user()->getUserRepo()->persist($user);

        $oauser = new OAuthUser([
            'user' => $user,
            'provider' => $provider->getId(),
            'identifier' => $info->identifier()
        ]);

        $this->modules->oauth()->getOAuthUserRepo()->persist($oauser);

        return $user;
    }
}
