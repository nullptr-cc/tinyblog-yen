<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\Handler;
use TinyBlog\Web\RequestData\ArticleDeleteData;
use TinyBlog\User\User;

class DeleteHandler extends Handler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $responder = $this->modules->web()->getJsonResponder();

        $sentinel = $this->modules->web()->getSentinel();
        if ($sentinel->shallNotPass($request)) {
            return $responder->forbidden('Blocked');
        };

        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_AUTHOR) {
            return $responder->forbidden('Not authorized');
        };

        $data = ArticleDeleteData::createFromRequest($request);
        $repo = $this->modules->article()->getArticleRepo();

        if (!$repo->articleExists($data->getArticleId())) {
            return $responder->badParams(['article_id' => 'invalid article id']);
        };

        $article = $repo->getArticleById($data->getArticleId());

        try {
            $repo->deleteArticle($article);
        } catch (\Exception $ex) {
            return $responder->error('Something wrong: ' . $ex->getMessage());
        };

        return $responder->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
