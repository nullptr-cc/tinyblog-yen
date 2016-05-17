<?php

namespace TinyBlog\OAuth;

use TinyBlog\User\User;

class OAuthUser
{
    protected $user;
    protected $provider;
    protected $identifier;

    public function __construct(array $init_data = [])
    {
        if (isset($init_data['user'])) {
            $this->setUser($init_data['user']);
        };

        if (isset($init_data['provider'])) {
            $this->setProvider($init_data['provider']);
        };

        if (isset($init_data['identifier'])) {
            $this->setIdentifier($init_data['identifier']);
        };
    }

    protected function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    protected function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    protected function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function withUser(User $user)
    {
        $clone = clone $this;
        $clone->setUser($user);
        return $clone;
    }

    public function withProvider($provider)
    {
        $clone = clone $this;
        $clone->setProvider($provider);
        return $clone;
    }

    public function withIdentifier($identifier)
    {
        $clone = clone $this;
        $clone->setIdentifier($identifier);
        return $clone;
    }
}
