<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;
use TinyBlog\Domain\Model\Article;

class CreateHandler extends BaseHandler
{
    public function onGet(IServerRequest $request)
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
