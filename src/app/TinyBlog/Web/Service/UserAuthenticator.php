<?php

namespace TinyBlog\Web\Service;

use Yen\Session\Contract\ISession;
use TinyBlog\User\UserRepo;
use TinyBlog\User\User;

class UserAuthenticator
{
    protected $session;
    protected $user_srv;

    public function __construct(ISession $session, UserRepo $user_srv)
    {
        $this->session = $session;
        $this->user_srv = $user_srv;
    }

    /**
     * @return bool
     */
    public function authenticate($username, $password)
    {
        if (!$this->user_srv->usernameExists($username)) {
            return false;
        };

        $user = $this->user_srv->getByUsername($username);

        if (!password_verify($password, $user->password())) {
            return false;
        };

        return true;
    }

    /**
     * @return self
     */
    public function setAuthUser(User $user)
    {
        $this->session->getStorage('auth')->set('user_id', $user->getId());

        return $this;
    }

    /**
     * return User
     */
    public function getAuthUser()
    {
        $user_id = $this->session->getStorage('auth')->get('user_id', 0);

        if (!$this->user_srv->userExists($user_id)) {
            return new User(['role' => User::ROLE_NONE]);
        };

        return $this->user_srv->getById($user_id);
    }
}
