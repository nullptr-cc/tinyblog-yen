<?php

namespace TinyBlog\Web\Handler\Base\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Exception\AccessDenied;
use TinyBlog\Web\Handler\CommandHandler;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Article\EArticleNotExists;
use TinyBlog\User\User;

abstract class SaveHandler extends CommandHandler
{
    abstract protected function saveArticle(ArticleData $data);

    protected function checkAccess(IServerRequest $request)
    {
        if ($this->getAuthUser()->getRole() < User::ROLE_AUTHOR) {
            throw new AccessDenied('Not authorized');
        };
    }

    protected function handleRequest(IServerRequest $request)
    {
        $data = ArticleData::createFromRequest($request);
        $validator = $this->modules->web()->getArticleDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->getResponder()->badParams($vr->getErrors());
        };

        try {
            $article = $this->saveArticle($data);
        } catch (EArticleNotExists $ex) {
            return $this->getResponder()->badParams(['article_id' => 'Invalid article ID']);
        } catch (\Exception $ex) {
            return $this->getResponder()->error('Try again later: ' . $ex->getMessage());
        };

        return $this->getResponder()->ok([
            'article_url' => $this->modules->web()->getUrlBuilder()->buildArticleUrl($article)
        ]);
    }
}
