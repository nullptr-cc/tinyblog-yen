<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Domain\Model\Article;

class CreateHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->authenticator->getAuthUser();
        if (!$auth_user) {
            return $this->forbidden('Not authorized');
        };

        $article = new Article();

        return $this->ok(
            'Page/Article/Create',
            ['article' => $article]
        );
    }
}
