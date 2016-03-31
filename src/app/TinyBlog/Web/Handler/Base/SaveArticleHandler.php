<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Domain\Exception\ArticleNotFound;
use TinyBlog\Type\User;

abstract class SaveArticleHandler extends AjaxHandler
{
    abstract protected function saveArticle(ArticleData $data);

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

        $data = ArticleData::createFromRequest($request);
        $validator = $this->web->getArticleDataValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->badParams($vr->getErrors());
        };

        try {
            $article = $this->saveArticle($data);
        } catch (ArticleNotFound $ex) {
            return $this->badParams(['article_id' => 'Invalid article ID']);
        } catch (\Exception $ex) {
            return $this->error('Try again later: ' . $ex->getMessage());
        };

        return $this->ok([
            'article_url' => $this->web->getUrlBuilder()->buildArticleUrl($article)
        ]);
    }
}
