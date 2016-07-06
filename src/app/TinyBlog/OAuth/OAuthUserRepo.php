<?php

namespace TinyBlog\OAuth;

use TinyBlog\OAuth\DataAccess\OAuthUserStore;

class OAuthUserRepo
{
    private $store;

    public function __construct(OAuthUserStore $store)
    {
        $this->store = $store;
    }

    public function find($provider, $identifier)
    {
        $cond = ['provider' => $provider, 'identifier' => $identifier];
        return $this->store->fetch($cond);
    }

    public function persist(OAuthUser $oauser)
    {
        $this->store->insert($oauser);
        return $this;
    }
}
