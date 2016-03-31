<?php

namespace TinyBlog\Domain\User;

use TinyBlog\Type\User;
use TinyBlog\DataAccess\User\UserSaver;

class UserRepo
{
    protected $saver;

    public function __construct(UserSaver $saver)
    {
        $this->saver = $saver;
    }

    public function persist(User $user)
    {
        $result = $this->saver->insert($user);

        return $user->withId($result->id);
    }
}
