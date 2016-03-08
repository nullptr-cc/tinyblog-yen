<?php

namespace TinyBlog\Web\RequestData;

class SignInData
{
    protected $username;
    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public static function createFromRequest($request)
    {
        $data = $request->getParsedBody();
        $username = $password = '';

        if (array_key_exists('username', $data)) {
            $username = $data['username'];
        };

        if (array_key_exists('password', $data)) {
            $password = $data['password'];
        };

        return new self($username, $password);
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }
}
