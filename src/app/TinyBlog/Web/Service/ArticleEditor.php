<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Article\Article;
use TinyBlog\Article\Content;
use TinyBlog\User\User;
use TinyBlog\Web\RequestData\ArticleData;
use DateTimeInterface;

class ArticleEditor
{
    protected $repo;
    protected $md_transformer;
    protected $teaser_maker;

    public function __construct($repo, $md_transformer, $teaser_maker)
    {
        $this->repo = $repo;
        $this->md_transformer = $md_transformer;
        $this->teaser_maker = $teaser_maker;
    }

    public function createArticle(ArticleData $data, User $author, DateTimeInterface $created_at)
    {
        $init_data = [
            'author' => $author,
            'created_at' => $created_at
        ];

        $article = $this->fillArticle(new Article($init_data), $data);

        return $this->repo->persistArticle($article);
    }

    public function updateArticle(Article $article, ArticleData $data)
    {
        $article = $this->fillArticle($article, $data);

        return $this->repo->persistArticle($article);
    }

    protected function fillArticle(Article $article, ArticleData $data)
    {
        $body_html = $this->md_transformer->toHtml($data->getBody());
        $body = new Content($data->getBody(), $body_html);
        $teaser = $this->teaser_maker->makeTeaser($body_html);

        return $article->withTitle($data->getTitle())
                       ->withBody($body)
                       ->withTeaser($teaser);
    }
}
