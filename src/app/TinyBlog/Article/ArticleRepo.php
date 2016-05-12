<?php

namespace TinyBlog\Article;

class ArticleRepo
{
    protected $store;
    protected $fetcher;

    public function __construct(
        DataAccess\ArticleStore $store,
        DataAccess\ArticleFetcher $fetcher
    ) {
        $this->store = $store;
        $this->fetcher = $fetcher;
    }

    public function persistArticle(Article $article)
    {
        if ($article->getId() != 0) {
            $this->store->updateArticle($article);
            return $article;
        };

        $result = $this->store->insertArticle($article);
        return $article->withId($result->id);
    }

    public function deleteArticle(Article $article)
    {
        return $this->store->deleteArticle($article);
    }

    /**
     * @return Article
     */
    public function getArticleById($article_id)
    {
        $articles = $this->fetcher->fetchById($article_id);

        if (!count($articles)) {
            throw new EArticleNotFound($article_id);
        };

        return $articles[0];
    }

    /**
     * @return stdClass { page_count : int, articles : Article[] }
     */
    public function getArticlesListRange($order, $page_num, $per_page)
    {
        $count = $this->fetcher->count();
        $page_count = ceil($count / $per_page);

        $return = (object)['page_count' => $page_count, 'articles' => []];

        if ($page_num < 1 || ($page_count && $page_num > $page_count)) {
            return $return;
        };

        $return->articles = $this->fetcher->fetch($order, ($page_num - 1) * $per_page, $per_page);

        return $return;
    }
}
