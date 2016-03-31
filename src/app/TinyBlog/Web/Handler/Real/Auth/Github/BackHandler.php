<?php

namespace TinyBlog\Web\Handler\Real\Auth\Github;

use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Type\User;
use TinyBlog\Type\OAuthUser;
use TinyBlog\Domain\OAuth\IProvider;
use TinyBlog\Domain\OAuth\UserInfo;

class BackHandler extends CommonHandler
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

        $provider = $this->domain->getOAuthProviderGithub();

        $code = $provider->grabAuthCode($request);
        if ($code == '') {
            return $this->forbidden();
        };

        $token = $provider->getAccessToken($code);
        if ($token == '') {
            return $this->forbidden();
        };

        $info = $provider->getUserInfo($token);
        if ($info->identifier() == 0) {
            return $this->error();
        };

        $repo = $this->domain->getOAuthUserRepo();
        $res = $repo->find($provider->getId(), $info->identifier());

        if (!count($res)) {
            $user = $this->createUser($provider, $info);
        } else {
            $user = $res[0]->getUser();
        };

        $this->web->getSession()->start();
        $this->web->getUserAuthenticator()->setAuthUser($user);

        return $this->redirect($this->web->getUrlBuilder()->buildMainPageUrl());
    }

    protected function createUser(IProvider $provider, UserInfo $info)
    {
        $user = new User([
            'username' => sprintf('oauth:%d:%d', $provider->getId(), $info->identifier()),
            'nickname' => $info->name(),
            'password' => 'xxx',
            'role'     => User::ROLE_CONSUMER
        ]);

        $user = $this->domain->getUserRepo()->persist($user);

        $oauser = new OAuthUser([
            'user' => $user,
            'provider' => $provider->getId(),
            'identifier' => $info->identifier()
        ]);

        $this->domain->getOAuthUserRepo()->persist($oauser);

        return $user;
    }
}
