<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\BaseHandler;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class CreateHandler extends BaseHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getHtmlResponder();

        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_AUTHOR) {
            return $responder->forbidden('Not authorized');
        };

        $article = new Article();

        return $responder->ok(
            'Page/Article/Create',
            ['article' => $article]
        );
    }
}
