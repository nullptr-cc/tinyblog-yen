<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
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
        $data = ArticleDeleteData::createFromRequest($request);
        $repo = $this->modules->article()->getArticleRepo();

        if (!$repo->articleExists($data->getArticleId())) {
            return $this->getResponder()->badParams(['article_id' => 'invalid article id']);
        };

        $article = $repo->getArticleById($data->getArticleId());

        try {
            $repo->deleteArticle($article);
        } catch (\Exception $ex) {
            return $this->getResponder()->error('Something wrong: ' . $ex->getMessage());
        };

        return $this->getResponder()->ok([
            'redirect_url' => $this->modules->web()->getUrlBuilder()->buildMainPageUrl()
        ]);
    }
}
