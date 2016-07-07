<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Article\Exception\ArticleNotExists;
use TinyBlog\Article\Article;
use TinyBlog\Web\Handler\QueryHandler;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\RequestData\ArticleViewData;

class EditHandler extends QueryHandler
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
            $this->checkEditAccess($article);
        } catch (ArticleNotExists $ex) {
            return $this->getResponder()->badParams('Invalid article ID');
        } catch (AccessDenied $ex) {
            return $this->getResponder()->forbidden('');
        }

        return $this->getResponder()->ok(
            'Page/Article/Edit',
            ['article' => $article]
        );
    }

    private function takeArticleByRequest(IServerRequest $request)
    {
        $data = ArticleViewData::createFromRequest($request);
        $repo = $this->modules->article()->getArticleRepo();
        $article = $repo->getArticleById($data->getArticleId());

        return $article;
    }

    private function checkEditAccess(Article $article)
    {
        if ($this->getAuthUser()->getId() != $article->author()->getId()) {
            throw new AccessDenied('Auth user is not author');
        };
    }
}
