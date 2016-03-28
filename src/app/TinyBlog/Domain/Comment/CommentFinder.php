<?php

namespace TinyBlog\Domain\Comment;

use TinyBlog\Type\Article;
use TinyBlog\DataAccess\DataAccessRegistry;

class CommentFinder
{
    protected $dar;

    public function __construct(DataAccessRegistry $dar)
    {
        $this->dar = $dar;
    }

    public function getArticleComments(Article $article)
    {
        $fetcher = $this->dar->getCommentWithAuthorFetcher();

        return $fetcher->fetchByArticle($article, ['created_at' => 'asc']);
    }
}
