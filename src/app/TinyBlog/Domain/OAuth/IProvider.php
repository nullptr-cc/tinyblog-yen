<?php

namespace TinyBlog\Domain\OAuth;

use Yen\Http\Contract\IServerRequest;

interface IProvider
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return IUri
     */
    public function getAuthUrl();

    /**
     * @return string
     */
    public function grabAuthCode(IServerRequest $request);

    /**
     * @return string
     */
    public function getAccessToken($code);

    /**
     * @return UserInfo
     */
    public function getUserinfo($access_token);
}
