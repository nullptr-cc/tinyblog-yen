<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Web\RequestData\ArticleViewData;
use TinyBlog\User\User;

class EditHandler extends CommonHandler
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

        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->modules->article()->getArticleRepo();

        if (!$finder->articleExists($data->getArticleId())) {
            return $this->badParams('Invalid article ID');
        };

        $article = $finder->getArticleById($data->getArticleId());

        if ($auth_user->getId() != $article->author()->getId()) {
            return $this->forbidden('');
        };

        return $this->ok(
            'Page/Article/Edit',
            ['article' => $article]
        );
    }
}
