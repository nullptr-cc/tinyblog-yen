<?php

namespace TinyBlog\Domain\Article;

use TinyBlog\Type\Article;

class ArticleRepo
{
    protected $saver;

    public function __construct($saver)
    {
        $this->saver = $saver;
    }

    public function persistArticle(Article $article)
    {
        if ($article->getId() != 0) {
            $this->saver->updateArticle($article);
            return $article;
        };

        $result = $this->saver->insertArticle($article);
        return $article->withId($result->id);
    }

    public function deleteArticle(Article $article)
    {
        return $this->saver->deleteArticle($article);
    }
}
