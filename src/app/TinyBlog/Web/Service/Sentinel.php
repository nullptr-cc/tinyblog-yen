<?php

namespace TinyBlog\Web\Service;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Uri;

class Sentinel
{
    const RBYTES = 48;
    const CSRF_TOKEN = 'csrftkn';

    private $rng;
    private $valid_origin_host;

    public function __construct($valid_origin_host)
    {
        $this->valid_origin_host = $valid_origin_host;

        if (function_exists('random_bytes')) {
            $this->rng = 'random_bytes';
            return;
        };

        if (function_exists('openssl_random_pseudo_bytes')) {
            $this->rng = 'openssl_random_pseudo_bytes';
            return;
        };

        throw new \RuntimeException('No RNG');
    }

    public function getCsrfTokenName()
    {
        return self::CSRF_TOKEN;
    }

    public function newCsrfToken()
    {
        $bytes = call_user_func($this->rng, self::RBYTES);
        if ($bytes === false) {
            throw new \RuntimeException('No random bytes generated');
        };

        return rtrim(base64_encode($bytes), '=');
    }

    public function shallNotPass(IServerRequest $request)
    {
        return !$this->checkOrigin($request) || !$this->checkCsrfToken($request);
    }

    private function checkOrigin(IServerRequest $request)
    {
        if ($request->hasHeader('origin')) {
            $origin = Uri::createFromString($request->getHeader('origin'));
        } elseif ($request->hasHeader('referer')) {
            $origin = Uri::createFromString($request->getHeader('referer'));
        } else {
            return false;
        };

        return $origin->getHost() == $this->valid_origin_host;
    }

    private function checkCsrfToken(IServerRequest $request)
    {
        $post = $request->getParsedBody();
        if (!array_key_exists(self::CSRF_TOKEN, $post)) {
            return false;
        };

        $hdr_name = 'x-' . self::CSRF_TOKEN;
        if (!$request->hasHeader($hdr_name)) {
            return false;
        };

        $hdr_value = $request->getHeader($hdr_name);
        $post_value = $post[self::CSRF_TOKEN];

        return $hdr_value == $post_value;
    }
}
