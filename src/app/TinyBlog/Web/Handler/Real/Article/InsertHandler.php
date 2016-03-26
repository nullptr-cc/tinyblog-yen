<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\SaveArticleHandler;
use TinyBlog\Web\RequestData\ArticleData;

class InsertHandler extends SaveArticleHandler
{
    protected function saveArticle(ArticleData $data)
    {
        $editor = $this->domain->getArticleEditor();

        return $editor->createArticle(
            $data->withCreatedAt(new \DateTimeImmutable())->dump(),
            $this->getAuthUser()
        );
    }
}
