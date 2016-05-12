<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class CreateHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_AUTHOR) {
            return $this->forbidden('Not authorized');
        };

        $article = new Article();

        return $this->ok(
            'Page/Article/Create',
            ['article' => $article]
        );
    }
}
