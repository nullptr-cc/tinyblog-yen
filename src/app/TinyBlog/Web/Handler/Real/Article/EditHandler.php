<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use TinyBlog\Web\Handler\Base\BaseHandler;
use TinyBlog\Web\RequestData\ArticleViewData;

class EditHandler extends BaseHandler
{
    public function onGet(IServerRequest $request)
    {
        $auth_user = $this->authenticator->getAuthUser();
        if (!$auth_user) {
            return $this->forbidden('Not authorized');
        };

        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->domain_registry->getArticleFinder();

        try {
            $article = $finder->getArticle($data->getArticleId());
        } catch (\InvalidArgumentException $ex) {
            return $this->badParams('Invalid article ID');
        };

        if ($auth_user->getId() != $article->author()->getId()) {
            return $this->forbidden('');
        };

        return $this->ok('Page/Article/Edit', $article);
    }
}
