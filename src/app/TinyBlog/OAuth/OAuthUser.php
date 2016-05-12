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
        return $this->with(['user' => $user]);
    }

    public function withProvider($provider)
    {
        return $this->with(['provider' => $provider]);
    }

    public function withIdentifier($identifier)
    {
        return $this->with(['identifier' => $identifier]);
    }

    protected function with(array $replacement)
    {
        return new self(array_merge($this->parts, $replacement));
    }

    protected function parts()
    {
        return [
            'user' => $this->user,
            'provider' => $this->provider,
            'identifier' => $this->identifier
        ];
    }
}
