<?php

namespace TinyBlog\User;

class User
{
    const ROLE_NONE     = 0;
    const ROLE_CONSUMER = 1;
    const ROLE_AUTHOR   = 2;

    protected $id;
    protected $nickname;
    protected $username;
    protected $password;
    protected $role;

    public function __construct(array $init_data = [])
    {
        if (isset($init_data['id'])) {
            $this->setId($init_data['id']);
        };

        if (isset($init_data['nickname'])) {
            $this->setNickname($init_data['nickname']);
        };

        if (isset($init_data['username'])) {
            $this->setUsername($init_data['username']);
        };

        if (isset($init_data['password'])) {
            $this->setPassword($init_data['password']);
        };

        if (isset($init_data['role'])) {
            $this->setRole($init_data['role']);
        } else {
            $this->setRole(self::ROLE_NONE);
        };
    }

    protected function setId($id)
    {
        $this->id = intval($id);
    }

    protected function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    protected function setUsername($username)
    {
        $this->username = $username;
    }

    protected function setPassword($password)
    {
        $this->password = $password;
    }

    protected function setRole($role)
    {
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function withId($id)
    {
        $clone = clone $this;
        $clone->setId($id);
        return $clone;
    }

    public function withNickname($nickname)
    {
        $clone = clone $this;
        $clone->setNickname($nickname);
        return $clone;
    }

    public function withUsername($username)
    {
        $clone = clone $this;
        $clone->setUsername($username);
        return $clone;
    }

    public function withPassword($password)
    {
        $clone = clone $this;
        $clone->setPassword($password);
        return $clone;
    }

    public function withRole($role)
    {
        $clone = clone $this;
        $clone->setRole($role);
        return $clone;
    }

    public function id()
    {
        return $this->getId();
    }

    public function nickname()
    {
        return $this->getNickname();
    }

    public function username()
    {
        return $this->getUsername();
    }

    public function password()
    {
        return $this->getPassword();
    }

    public function role()
    {
        return $this->role;
    }
}
