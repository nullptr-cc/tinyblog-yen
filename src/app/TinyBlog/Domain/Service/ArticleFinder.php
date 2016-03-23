<?php

namespace TinyBlog\Domain\Service;

use TinyBlog\Domain\Model\Article;
use TinyBlog\Domain\Model\User;
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
        $raw = $fetcher->findById($article_id);
        if (!$raw) {
            throw new \InvalidArgumentException('invalid article id');
        };

        return $this->makeArticleWithUser($raw);
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

        $rows = $fetcher->find($order, ($page_num - 1) * $per_page, $per_page);
        foreach ($rows as $row) {
            $return->articles[] = $this->makeArticleWithUser($row);
        };

        return $return;
    }

    protected function makeArticleWithUser(array $data)
    {
        $author = new User([
            'id' => $data['author_id'],
            'nickname' => $data['nickname']
        ]);

        $article = new Article([
            'id' => $data['id'],
            'author' => $author,
            'title' => $data['title'],
            'body' => new Content($data['body_raw'], $data['body_html']),
            'teaser' => $data['teaser'],
            'created_at' => new \DateTimeImmutable($data['created_at'] . 'Z')
        ]);

        return $article;
    }
}
