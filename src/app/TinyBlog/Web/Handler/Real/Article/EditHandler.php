<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\Web\RequestData\ArticleViewData;
use TinyBlog\User\User;

class EditHandler extends Handler
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

        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->modules->article()->getArticleRepo();

        if (!$finder->articleExists($data->getArticleId())) {
            return $responder->badParams('Invalid article ID');
        };

        $article = $finder->getArticleById($data->getArticleId());

        if ($auth_user->getId() != $article->author()->getId()) {
            return $responder->forbidden('');
        };

        return $responder->ok(
            'Page/Article/Edit',
            ['article' => $article]
        );
    }
}
