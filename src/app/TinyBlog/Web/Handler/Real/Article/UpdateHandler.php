<?php

namespace TinyBlog\Web\Handler\Real\Article;

use TinyBlog\Web\Handler\Base\SaveArticleHandler;
use TinyBlog\Type\IArticleInitData;

class UpdateHandler extends SaveArticleHandler
{
    protected function saveArticle(IArticleInitData $data)
    {
        $finder = $this->domain_registry->getArticleFinder();
        $editor = $this->domain_registry->getArticleEditor();
        $article = $finder->getArticle($data->getArticleId());

        return $editor->updateArticle($article, $data);
    }
}
