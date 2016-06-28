<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\Article\SaveHandler;
use TinyBlog\Web\RequestData\ArticleData;

class InsertHandler extends SaveHandler
{
    protected function saveArticle(ArticleData $data)
    {
        $editor = $this->modules->web()->getArticleEditor();
        $chrono = $this->modules->tools()->getChrono();

        return $editor->createArticle($data, $this->getAuthUser(), $chrono->now());
    }
}
