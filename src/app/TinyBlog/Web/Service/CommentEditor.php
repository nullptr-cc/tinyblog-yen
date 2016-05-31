<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Comment\CommentRepo;
use TinyBlog\Article\Article;
use TinyBlog\User\User;
use TinyBlog\Comment\Comment;
use TinyBlog\Web\RequestData\CommentData;
use DateTimeInterface as IDateTime;

class CommentEditor
{
    protected $repo;

    public function __construct(CommentRepo $repo)
    {
        $this->repo = $repo;
    }

    public function createComment(
        CommentData $data,
        Article $article,
        User $author,
        IDateTime $created_at
    ) {
        $init_data = [
            'article' => $article,
            'author' => $author,
            'body' => $data->getBody(),
            'created_at' => $created_at
        ];

        return $this->repo->persistComment(new Comment($init_data));
    }
}
