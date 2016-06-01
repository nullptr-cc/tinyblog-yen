<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Handler\Contract\IHandler;
use Yen\Handler\HandlerResponseHelpers;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Uri;
use TinyBlog\Modules;

abstract class Handler implements IHandler
{
    use HandlerResponseHelpers;

    protected $modules;

    public function __construct(Modules $modules)
    {
        $this->modules = $modules;
    }

    protected function getAuthUser()
    {
        return $this->modules->web()->getUserAuthenticator()->getAuthUser();
    }

    protected function checkReferer(IServerRequest $request)
    {
        if (!$request->hasHeader('referer')) {
            return false;
        };

        $referer = Uri::createFromString($request->getHeader('referer'));
        $base = Uri::createFromString($this->modules->web()->getSettings()->get('base_url'));

        return $referer->getHost() == $base->getHost();
    }
}
