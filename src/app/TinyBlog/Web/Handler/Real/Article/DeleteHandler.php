<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\ArticleDeleteData;
use TinyBlog\Domain\Exception\ArticleNotFound;
use TinyBlog\Type\User;

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
        $finder = $this->domain->getArticleFinder();
        $repo = $this->domain->getArticleRepo();

        try {
            $article = $finder->getArticleById($data->getArticleId());
            $repo->deleteArticle($article);
        } catch (ArticleNotFound $ex) {
            return $this->badParams(['article_id' => 'invalid article id']);
        } catch (\Exception $ex) {
            return $this->error('Something wrong: ' . $ex->getMessage());
        };

        return $this->ok([
            'redirect_url' => $this->web->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
