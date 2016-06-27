<?php

namespace TinyBlog\Web\Service;

use TinyBlog\User\User;
use TinyBlog\User\UserRepo;
use TinyBlog\OAuth\OAuthUser;
use TinyBlog\OAuth\OAuthUserRepo;
use TinyBlog\OAuth\UserInfo;
use TinyBlog\OAuth\Contract\IProvider;

class OAuthUserCreator
{
    private $user_repo;
    private $oauth_user_repo;

    public function __construct(UserRepo $user_repo, OAuthUserRepo $oauth_user_repo)
    {
        $this->user_repo = $user_repo;
        $this->oauth_user_repo = $oauth_user_repo;
    }

    public function createUser(IProvider $provider, UserInfo $info)
    {
        $user = new User([
            'username' => sprintf('oauth:%d:%s', $provider->getId(), $info->identifier()),
            'nickname' => $info->name(),
            'password' => 'xxx',
            'role'     => User::ROLE_CONSUMER
        ]);

        $user = $this->user_repo->persist($user);

        $oauser = new OAuthUser([
            'user' => $user,
            'provider' => $provider->getId(),
            'identifier' => $info->identifier()
        ]);

        $this->oauth_user_repo->persist($oauser);

        return $user;
    }
}
