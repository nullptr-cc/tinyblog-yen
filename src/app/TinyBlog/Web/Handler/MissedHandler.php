<?php

namespace TinyBlog\Web\Handler;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;

class MissedHandler extends BaseHandler
{
    public function handle(IServerRequest $request)
    {
        return $this->notFound('page not found');
    }
}
