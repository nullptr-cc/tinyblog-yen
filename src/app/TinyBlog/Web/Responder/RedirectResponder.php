<?php

namespace TinyBlog\Web\Responder;

use Yen\Http\Contract\IUri;
use Yen\Http\Contract\IResponse;
use Yen\Http\Response;

class RedirectResponder
{
    public function redirect(IUri $url)
    {
        return Response::movedTemporary()->withHeader('Location', $url);
    }

    public function redirectPermanent(IUri $url)
    {
        return Response::movedPermanently()->withHeader('Location', $url);
    }
}
