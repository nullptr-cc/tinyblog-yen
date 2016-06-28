<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Article\Article;
use TinyBlog\Article\Content;
use TinyBlog\Article\ArticleRepo;
use TinyBlog\User\User;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Tools\Contract\IMarkdownTransformer;
use TinyBlog\Tools\TeaserMaker;
use DateTimeInterface as IDateTime;

class ArticleEditor
{
    protected $repo;
    protected $md_transformer;
    protected $teaser_maker;

    public function __construct(
        ArticleRepo $repo,
        IMarkdownTransformer $md_transformer,
        TeaserMaker $teaser_maker
    ) {
        $this->repo = $repo;
        $this->md_transformer = $md_transformer;
        $this->teaser_maker = $teaser_maker;
    }

    public function createArticle(ArticleData $data, User $author, IDateTime $created_at)
    {
        $article = new Article([
            'author' => $author,
            'created_at' => $created_at
        ]);

        $article = $this->fillArticle($article, $data);

        return $this->repo->insertArticle($article);
    }

    public function updateArticle(Article $article, ArticleData $data)
    {
        $article = $this->fillArticle($article, $data);

        return $this->repo->updateArticle($article);
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
