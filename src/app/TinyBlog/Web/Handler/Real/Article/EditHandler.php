<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\CommonHandler;
use TinyBlog\Web\RequestData\ArticleViewData;
use TinyBlog\Domain\Exception\ArticleNotFound;

class EditHandler extends CommonHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->getAuthUser();
        if (!$auth_user) {
            return $this->forbidden('Not authorized');
        };

        $data = ArticleViewData::createFromRequest($request);
        $finder = $this->domain->getArticleFinder();

        try {
            $article = $finder->getArticleById($data->getArticleId());
        } catch (ArticleNotFound $ex) {
            return $this->badParams('Invalid article ID');
        };

        if ($auth_user->getId() != $article->author()->getId()) {
            return $this->forbidden('');
        };

        return $this->ok(
            'Page/Article/Edit',
            ['article' => $article]
        );
    }
}
