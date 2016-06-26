<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\RequestData\ArticleViewData;
use TinyBlog\User\User;

class EditHandler extends QueryHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() < User::ROLE_AUTHOR) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->modules->article()->getArticleRepo();

        if (!$finder->articleExists($data->getArticleId())) {
            return $this->getResponder()->badParams('Invalid article ID');
        };

        $article = $finder->getArticleById($data->getArticleId());

        if ($this->getAuthUser()->getId() != $article->author()->getId()) {
            return $this->getResponder()->forbidden('');
        };

        return $this->getResponder()->ok(
            'Page/Article/Edit',
            ['article' => $article]
        );
    }
}
