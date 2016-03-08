<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\SaveArticleHandler;
use TinyBlog\Type\IArticleInitData;

class InsertHandler extends SaveArticleHandler
{
    protected function saveArticle(IArticleInitData $data)
    {
        $editor = $this->domain_registry->getArticleEditor();

        return $editor->createArticle(
            $data->withCreatedAt(new \DateTimeImmutable()),
            $this->getAuthUser()
        );
    }
}
