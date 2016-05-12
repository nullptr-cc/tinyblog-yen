<?php

namespace TinyBlog\Comment;

use TinyBlog\Article\Article;

class CommentRepo
{
    protected $store;
    protected $fetcher;

    public function __construct(
        DataAccess\CommentStore $store,
        DataAccess\CommentFetcher $fetcher
    ) {
        $this->store = $store;
        $this->fetcher = $fetcher;
    }

    public function persistComment(Comment $comment)
    {
        if ($comment->getId() != 0) {
            $this->store->updateComment($comment);
            return $comment;
        };

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
