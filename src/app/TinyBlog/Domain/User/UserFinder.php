<?php

namespace TinyBlog\Domain\User;

use TinyBlog\DataAccess\DataAccessRegistry;

class UserFinder
{
    protected $dar;

    public function __construct(DataAccessRegistry $dar)
    {
        $this->dar = $dar;
    }

    public function getById($id)
    {
        $fetcher = $this->dar->getUserFetcher();
        $user = $fetcher->fetchById($id);
        if (!count($user)) {
            throw new \InvalidArgumentException('invalid user id');
        };

        return $user[0];
    }

    public function getByUsername($username)
    {
        $fetcher = $this->dar->getUserFetcher();
        $user = $fetcher->fetchByUsername($username);
        if (!count($user)) {
            throw new \InvalidArgumentException('invalid username');
        };

        return $user[0];
    }
}
