<?php

namespace TinyBlog\Domain\Service;

use TinyBlog\DataAccess\DataAccessRegistry;
use TinyBlog\Domain\Model\User;

class UserFinder
{
    protected $da;

    public function __construct(DataAccessRegistry $da)
    {
        $this->da = $da;
    }

    public function getById($id)
    {
        $fetcher = $this->da->getUserFetcher();
        $raw = $fetcher->findById($id);
        if (!$raw) {
            throw new \InvalidArgumentException('invalid user id');
        };

        return $this->makeUser($raw);
    }

    public function getByUsername($username)
    {
        $fetcher = $this->da->getUserFetcher();
        $raw = $fetcher->findByUsername($username);
        if (!$raw) {
            throw new \InvalidArgumentException('invalid username');
        };

        return $this->makeUser($raw);
    }

    protected function makeUser(array $raw)
    {
        return new User($raw);
    }
}
