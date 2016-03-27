<?php

namespace TinyBlog\Domain\Article;

use TinyBlog\Type\Article;
use TinyBlog\Type\User;
use TinyBlog\Type\Content;
use TinyBlog\DataAccess\DataAccessRegistry;
use TinyBlog\Domain\Exception\ArticleNotFound;

class ArticleFinder
{
    protected $dar;

    public function __construct(DataAccessRegistry $dar)
    {
        $this->dar = $dar;
    }

    /**
     * @return Article
     */
    public function getArticleById($article_id)
    {
        $fetcher = $this->dar->getArticleWithUserFetcher();
        $articles = $fetcher->fetchById($article_id);

        if (!count($articles)) {
            throw new ArticleNotFound($article_id);
        };

        return $articles[0];
    }

    /**
     * @return stdClass { page_count : int, articles : Article[] }
     */
    public function getArticlesListRange($order, $page_num, $per_page)
    {
        $fetcher = $this->dar->getArticleWithUserFetcher();
        $count = $fetcher->count();
        $page_count = ceil($count / $per_page);

        $return = (object)['page_count' => $page_count, 'articles' => []];

        if ($page_num < 1 || ($page_count && $page_num > $page_count)) {
            return $return;
        };

        $return->articles = $fetcher->fetch($order, ($page_num - 1) * $per_page, $per_page);

        return $return;
    }
}
