<?php

namespace TinyBlog\Web\Service;

use Yen\Web\Session\Contract\ISession;
use TinyBlog\Domain\Service\UserFinder;
use TinyBlog\Type\IUser;

class UserAuthenticator
{
    protected $session;
    protected $user_srv;

    public function __construct(ISession $session, UserFinder $user_srv)
    {
        $this->session = $session;
        $this->user_srv = $user_srv;
    }

    public function authenticate($username, $password)
    {
        $user = $this->user_srv->getByUsername($username);
        if (!password_verify($password, $user->password())) {
            throw new \InvalidArgumentException('invalid password');
        };

        return $user;
    }

    public function setAuthUser(IUser $user)
    {
        $this->session->getStorage('auth')->set('user_id', $user->getId());

        return $this;
    }

    public function getAuthUser()
    {
        $user_id = $this->session->getStorage('auth')->get('user_id', 0);
        try {
            return $this->user_srv->getById($user_id);
        } catch (\InvalidArgumentException $ex) {
            return null;
        };
    }
}
