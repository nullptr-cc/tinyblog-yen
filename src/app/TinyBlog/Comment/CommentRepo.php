<?php

namespace TinyBlog\Comment;

use TinyBlog\Article\Article;
use TinyBlog\Comment\DataAccess\CommentFetcher;
use TinyBlog\Comment\DataAccess\CommentStore;

class CommentRepo
{
    private $store;
    private $fetcher;

    public function __construct(CommentStore $store, CommentFetcher $fetcher)
    {
        $this->store = $store;
        $this->fetcher = $fetcher;
    }

    public function persistComment(Comment $comment)
    {
        $result = $this->store->insertComment($comment);
        return $comment->withId($result->id);
    }

    public function deleteComment(Comment $comment)
    {
        return $this->store->deleteComment($comment);
    }

    public function getArticleComments(Article $article)
    {
        return $this->fetcher->fetchByArticle($article, ['created_at' => 'asc']);
    }
}
