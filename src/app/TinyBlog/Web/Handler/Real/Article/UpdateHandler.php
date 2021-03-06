<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\Article\SaveHandler;
use TinyBlog\Web\RequestData\ArticleData;

class UpdateHandler extends SaveHandler
{
    protected function saveArticle(ArticleData $data)
    {
        $repo = $this->modules->article()->getArticleRepo();
        $editor = $this->modules->web()->getArticleEditor();
        $article = $repo->getArticleById($data->getArticleId());

        return $editor->updateArticle($article, $data);
    }
}
