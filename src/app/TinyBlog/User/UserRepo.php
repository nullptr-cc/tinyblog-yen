<?php

namespace TinyBlog\User;

use TinyBlog\User\DataAccess\UserStore;
use TinyBlog\User\Exception\UserNotExists;

class UserRepo
{
    private $store;

    public function __construct(UserStore $store)
    {
        $this->store = $store;
    }

    public function persist(User $user)
    {
        $result = $this->store->insert($user);

        return $user->withId($result->id);
    }

    public function userExists($user_id)
    {
        return $this->store->countById($user_id) != 0;
    }

    public function usernameExists($username)
    {
        return $this->store->countByUsername($username) != 0;
    }

    public function getById($id)
    {
        $result = $this->store->fetchById($id);

        if (!count($result)) {
            throw new UserNotExists();
        };

        return $result[0];
    }

    public function getByUsername($username)
    {
        $result = $this->store->fetchByUsername($username);

        if (!count($result)) {
            throw new UserNotExists();
        };

        return $result[0];
    }
}
