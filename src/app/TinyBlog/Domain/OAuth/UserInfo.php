<?php

namespace TinyBlog\Domain\OAuth;

class UserInfo
{
    protected $identifier;
    protected $name;
    protected $email;

    public function __construct($identifier, $name, $email)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->email = $email;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function name()
    {
        return $this->name;
    }

    public function email()
    {
        return $this->email;
    }
}
