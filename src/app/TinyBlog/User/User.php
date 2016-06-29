<?php

namespace TinyBlog\User;

class User
{
    private $id;
    private $nickname;
    private $username;
    private $password;
    private $role;

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
            $this->setRole(UserRole::guest());
        };
    }

    private function setId($id)
    {
        $this->id = intval($id);
    }

    private function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    private function setUsername($username)
    {
        $this->username = $username;
    }

    private function setPassword($password)
    {
        $this->password = $password;
    }

    private function setRole(UserRole $role)
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

    public function withRole(UserRole $role)
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

    public function isGuest()
    {
        return $this->role->value() == UserRole::GUEST;
    }

    public function isConsumer()
    {
        return $this->role->value() == UserRole::CONSUMER;
    }

    public function isAuthor()
    {
        return $this->role->value() == UserRole::AUTHOR;
    }

    public function isNotGuest()
    {
        return !$this->isGuest();
    }

    public function isNotAuthor()
    {
        return !$this->isAuthor();
    }

    public static function guest(array $init_data = [])
    {
        $init_data['role'] = UserRole::GUEST();
        return new self($init_data);
    }

    public static function consumer(array $init_data = [])
    {
        $init_data['role'] = UserRole::CONSUMER();
        return new self($init_data);
    }

    public static function author(array $init_data = [])
    {
        $init_data['role'] = UserRole::AUTHOR();
        return new self($init_data);
    }
}
