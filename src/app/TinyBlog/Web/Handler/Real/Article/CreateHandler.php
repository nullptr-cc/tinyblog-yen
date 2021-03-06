<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Article\Article;

class CreateHandler extends QueryHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->isNotAuthor()) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $article = new Article();

        return $this->getResponder()->ok(
            'Page/Article/Create',
            ['article' => $article]
        );
    }
}
