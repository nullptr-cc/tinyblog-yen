<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\ArticleDeleteData;
use TinyBlog\Article\EArticleNotFound;
use TinyBlog\User\User;

class DeleteHandler extends AjaxHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->getAuthUser();
        if ($auth_user->getRole() < User::ROLE_AUTHOR) {
            return $this->forbidden('Not authorized');
        };

        $data = ArticleDeleteData::createFromRequest($request);
        $repo = $this->modules->article()->getArticleRepo();

        try {
            $article = $repo->getArticleById($data->getArticleId());
            $repo->deleteArticle($article);
        } catch (EArticleNotFound $ex) {
            return $this->badParams(['article_id' => 'invalid article id']);
        } catch (\Exception $ex) {
            return $this->error('Something wrong: ' . $ex->getMessage());
        };

        return $this->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
