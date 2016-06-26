<?php

namespace TinyBlog\Article;

use TinyBlog\Article\DataAccess\ArticleFetcher;
use TinyBlog\Article\DataAccess\ArticleStore;
use TinyBlog\Article\Exception\ArticleNotExists;

class ArticleRepo
{
    private $store;
    private $fetcher;

    public function __construct(ArticleStore $store, ArticleFetcher $fetcher)
    {
        $this->store = $store;
        $this->fetcher = $fetcher;
    }

    public function insertArticle(Article $article)
    {
        $result = $this->store->insertArticle($article);
        return $article->withId($result->id);
    }

    public function updateArticle(Article $article)
    {
        $this->store->updateArticle($article);
        return $article;
    }

    public function deleteArticle(Article $article)
    {
        return $this->store->deleteArticle($article);
    }

    /**
     * @return bool
     */
    public function articleExists($article_id)
    {
        return $this->fetcher->countById($article_id) != 0;
    }

    /**
     * @return bool
     */
    public function articleNotExists($article_id)
    {
        return !$this->articleExists($article_id);
    }

    /**
     * @return Article
     */
    public function getArticleById($article_id)
    {
        $articles = $this->fetcher->fetchById($article_id);

        if (!count($articles)) {
            throw new ArticleNotExists($article_id);
        };

        return $articles[0];
    }

    /**
     * @return stdClass { page_count : int, articles : Article[] }
     */
    public function getArticlesListRange($order, $page_num, $per_page)
    {
        $count = $this->fetcher->countAll();
        $page_count = ceil($count / $per_page);

        $return = (object)['page_count' => $page_count, 'articles' => []];

        if (!$page_count || $page_num < 1 || $page_num > $page_count) {
            return $return;
        };

        $return->articles = $this->fetcher->fetch($order, ($page_num - 1) * $per_page, $per_page);

        return $return;
    }
}
