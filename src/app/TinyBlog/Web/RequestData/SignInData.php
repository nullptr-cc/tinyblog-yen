<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;
use Yen\Util\Extractor;

class SignInData
{
    protected $username;
    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $data = $request->getParsedBody();

        $username = Extractor::extractString($data, 'username');
        $password = Extractor::extractString($data, 'password');

        return new self($username, $password);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
