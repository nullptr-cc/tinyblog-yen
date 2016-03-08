<?php

namespace TinyBlog\Domain\Model;

use TinyBlog\Type\IUser;

class User implements IUser
{
    protected $id;
    protected $nickname;
    protected $username;
    protected $password;

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

    public function withId($id)
    {
        return $this->with(['id' => $id]);
    }

    public function withNickname($nickname)
    {
        return $this->with(['nickname' => $nickname]);
    }

    public function withUsername($username)
    {
        return $this->with(['username' => $username]);
    }

    public function withPassword($password)
    {
        return $this->with(['password' => $password]);
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

    protected function with(array $replacement)
    {
        return new self(
            array_merge($this->parts(), $replacement)
        );
    }

    protected function parts()
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}
