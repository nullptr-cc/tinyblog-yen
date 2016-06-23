<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Article\EArticleNotExists;
use TinyBlog\User\User;

abstract class SaveArticleHandler extends Handler
{
    abstract protected function saveArticle(ArticleData $data);

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

        $data = ArticleData::createFromRequest($request);
        $validator = $this->modules->web()->getArticleDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $responder->badParams($vr->getErrors());
        };

        try {
            $article = $this->saveArticle($data);
        } catch (EArticleNotExists $ex) {
            return $responder->badParams(['article_id' => 'Invalid article ID']);
        } catch (\Exception $ex) {
            return $responder->error('Try again later: ' . $ex->getMessage());
        };

        return $responder->ok([
            'article_url' => $this->modules->web()->getUrlBuilder()->buildArticleUrl($article)
        ]);
    }
}
