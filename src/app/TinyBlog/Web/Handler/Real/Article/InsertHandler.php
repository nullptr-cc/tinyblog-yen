<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\Article\SaveHandler;
use TinyBlog\Web\RequestData\ArticleData;
use DateTimeImmutable;

class InsertHandler extends SaveHandler
{
    protected function saveArticle(ArticleData $data)
    {
        $editor = $this->modules->web()->getArticleEditor();

        return $editor->createArticle($data, $this->getAuthUser(), new DateTimeImmutable());
    }
}
