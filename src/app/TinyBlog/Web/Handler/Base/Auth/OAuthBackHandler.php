<?php

namespace TinyBlog\Web\Handler\Base\Auth;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\User\User;
use TinyBlog\OAuth\UserInfo;
use TinyBlog\OAuth\Exception\AuthCodeNotTaken;
use TinyBlog\OAuth\Exception\AccessTokenNotTaken;
use TinyBlog\OAuth\Exception\UserInfoNotTaken;

abstract class OAuthBackHandler extends QueryHandler
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
        try {
            $this->takeUserInfoAndAuth($request);
        } catch (AuthCodeNotTaken $ex) {
            return $this->getResponder()->forbidden();
        } catch (AccessTokenNotTaken $ex) {
            return $this->getResponder()->forbidden();
        } catch (UserInfoNotTaken $ex) {
            return $this->getResponder()->error();
        };

        return $this->redirectToMainPage();
    }

    private function takeUserInfoAndAuth(IServerRequest $request)
    {
        $info = $this->takeUserInfo($request);
        $user = $this->findOrCreateUser($info);
        $this->authUser($user);
    }

    private function takeUserInfo(IServerRequest $request)
    {
        $provider = $this->getProvider();

        $code = $provider->grabAuthCode($request);
        $token = $provider->getAccessToken($code);
        $info = $provider->getUserInfo($token);

        return $info;
    }

    private function findOrCreateUser(UserInfo $info)
    {
        $provider = $this->getProvider();
        $repo = $this->modules->oauth()->getOAuthUserRepo();

        $res = $repo->find($provider->getId(), $info->identifier());
        if (count($res)) {
            $user = $res[0]->getUser();
        } else {
            $user = $this->modules->web()->getOAuthUserCreator()->createUser($provider, $info);
        };

        return $user;
    }

    private function authUser(User $user)
    {
        $web = $this->modules->web();
        $web->getSession()->start();
        $web->getUserAuthenticator()->setAuthUser($user);
    }

    private function redirectToMainPage()
    {
        $web = $this->modules->web();
        $redirector = $web->getRedirectResponder();

        return $redirector->redirect($web->getUrlBuilder()->buildMainPageUrl());
    }
}
