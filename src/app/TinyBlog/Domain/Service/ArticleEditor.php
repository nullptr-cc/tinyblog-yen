<?php

namespace TinyBlog\Domain\Service;

use TinyBlog\Type\IArticleInitData;
use TinyBlog\Type\IUser;
use TinyBlog\Type\IArticle;
use TinyBlog\Type\Content;
use TinyBlog\Domain\Model\Article;

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

    public function createArticle(IArticleInitData $data, IUser $author)
    {
        $init_data = [
            'author' => $author,
            'created_at' => $data->getCreatedAt()
        ];

        $article = $this->fillArticle(new Article($init_data), $data);
        $result = $this->saver->insertArticle($article);

        return $article->withId($result->id);
    }

    public function updateArticle(IArticle $article, IArticleInitData $data)
    {
        $article = $this->fillArticle($article, $data);
        $this->saver->updateArticle($article);

        return $article;
    }

    public function deleteArticle(IArticle $article)
    {
        return $this->saver->deleteArticle($article);
    }

    protected function fillArticle(IArticle $article, IArticleInitData $data)
    {
        $body_html = $this->md_transformer->toHtml($data->getBody());
        $body = new Content($data->getBody(), $body_html);
        $teaser = $this->teaser_maker->makeTeaser($body_html);

        return $article->withTitle($data->getTitle())
                       ->withBody($body)
                       ->withTeaser($teaser);
    }
}
