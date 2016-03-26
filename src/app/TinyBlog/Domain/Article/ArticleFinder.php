<?php

namespace TinyBlog\Domain\Article;

use TinyBlog\Type\Article;
use TinyBlog\Type\User;
use TinyBlog\Type\Content;

class ArticleFinder
{
    protected $da;

    public function __construct($da)
    {
        $this->da = $da;
    }

    public function getArticle($article_id)
    {
        $fetcher = $this->da->getArticleWithUserFetcher();
        $article = $fetcher->findById($article_id);
        if (!$article) {
            throw new \InvalidArgumentException('invalid article id');
        };

        return $article;
    }

    public function getArticlesListRange($order, $page_num, $per_page)
    {
        $fetcher = $this->da->getArticleWithUserFetcher();
        $count = $fetcher->count();
        $page_count = ceil($count / $per_page);

        $return = (object)['page_count' => $page_count, 'articles' => []];

        if ($page_num < 1 || ($page_count && $page_num > $page_count)) {
            return $return;
        };

        $return->articles = $fetcher->find($order, ($page_num - 1) * $per_page, $per_page);

        return $return;
    }
}
