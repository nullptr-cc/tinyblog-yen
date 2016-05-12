<?php

namespace TinyBlog\OAuth;

class OAuthUserRepo
{
    protected $store;

    public function __construct(DataAccess\OAuthUserStore $store)
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
