<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Article\Exception\ArticleNotExists;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\RequestData\ArticleDeleteData;

class DeleteHandler extends CommandHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->isNotAuthor()) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        try {
            $article = $this->takeArticleByRequest($request);
            $repo = $this->modules->article()->getArticleRepo();
            $repo->deleteArticle($article);
        } catch (ArticleNotExists $ex) {
            return $this->getResponder()->badParams(['msg' => 'invalid article id']);
        } catch (\Exception $ex) {
            return $this->getResponder()->error('Something wrong');
        };

        return $this->getResponder()->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }

    private function takeArticleByRequest(IServerRequest $request)
    {
        $data = ArticleDeleteData::createFromRequest($request);
        $repo = $this->modules->article()->getArticleRepo();
        $article = $repo->getArticleById($data->getArticleId());

        return $article;
    }
}
