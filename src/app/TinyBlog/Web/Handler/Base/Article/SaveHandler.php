<?php

namespace TinyBlog\Web\Handler\Base\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\Handler\Exception\InvalidData;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Article\Exception\ArticleNotExists;
use TinyBlog\User\User;

abstract class SaveHandler extends CommandHandler
{
    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() < User::ROLE_AUTHOR) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        try {
            $data = $this->takeAndValidateData($request);
            $article = $this->saveArticle($data);
        } catch (InvalidData $ex) {
            return $this->getResponder()->badParams($ex->getErrors());
        } catch (ArticleNotExists $ex) {
            return $this->getResponder()->badParams(['article_id' => 'Invalid article ID']);
        };

        return $this->getResponder()->ok([
            'article_url' => $this->modules->web()->getUrlBuilder()->buildArticleUrl($article)
        ]);
    }

    private function takeAndValidateData(IServerRequest $request)
    {
        $data = ArticleData::createFromRequest($request);
        $validator = $this->modules->web()->getArticleDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            throw new InvalidData($vr->getErrors());
        };

        return $data;
    }

    abstract protected function saveArticle(ArticleData $data);
}
