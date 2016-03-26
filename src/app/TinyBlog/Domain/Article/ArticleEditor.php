<?php

namespace TinyBlog\Domain\Article;

use TinyBlog\Type\Content;
use TinyBlog\Type\Article;
use TinyBlog\Type\User;

class ArticleEditor
{
    protected $saver;
    protected $md_transformer;
    protected $teaser_maker;

    public function __construct($saver, $md_transformer, $teaser_maker)
    {
        $this->saver = $saver;
        $this->md_transformer = $md_transformer;
        $this->teaser_maker = $teaser_maker;
    }

    public function createArticle(array $data, User $author)
    {
        $init_data = [
            'author' => $author,
            'created_at' => $data['created_at']
        ];

        $article = $this->fillArticle(new Article($init_data), $data);
        $result = $this->saver->insertArticle($article);

        return $article->withId($result->id);
    }

    public function updateArticle(Article $article, array $data)
    {
        $article = $this->fillArticle($article, $data);
        $this->saver->updateArticle($article);

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        return $this->saver->deleteArticle($article);
    }

    protected function fillArticle(Article $article, array $data)
    {
        $body_html = $this->md_transformer->toHtml($data['body']);
        $body = new Content($data['body'], $body_html);
        $teaser = $this->teaser_maker->makeTeaser($body_html);

        return $article->withTitle($data['title'])
                       ->withBody($body)
                       ->withTeaser($teaser);
    }
}
