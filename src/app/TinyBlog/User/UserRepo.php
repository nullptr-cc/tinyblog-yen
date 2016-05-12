<?php

namespace TinyBlog\User;

class UserRepo
{
    private $store;

    public function __construct(DataAccess\UserStore $store)
    {
        $this->store = $store;
    }

    public function persist(User $user)
    {
        $result = $this->store->insert($user);

        return $user->withId($result->id);
    }

    public function getById($id)
    {
        $result = $this->store->fetchById($id);

        if (!count($result)) {
            throw new \InvalidArgumentException('invalid user id');
        };

        return $result[0];
    }

    public function getByUsername($username)
    {
        $result = $this->store->fetchByUsername($username);

        if (!count($result)) {
            throw new \InvalidArgumentException('invalid username');
        };

        return $result[0];
    }
}
