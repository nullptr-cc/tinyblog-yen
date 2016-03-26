<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\SaveArticleHandler;
use TinyBlog\Web\RequestData\ArticleData;

class UpdateHandler extends SaveArticleHandler
{
    protected function saveArticle(ArticleData $data)
    {
        $finder = $this->domain->getArticleFinder();
        $editor = $this->domain->getArticleEditor();
        $article = $finder->getArticle($data->getArticleId());

        return $editor->updateArticle($article, $data->dump());
    }
}
