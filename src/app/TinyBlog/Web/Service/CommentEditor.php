<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Domain\Comment\CommentRepo;
use TinyBlog\Type\Article;
use TinyBlog\Type\User;
use TinyBlog\Type\Comment;
use TinyBlog\Web\RequestData\CommentData;
use DateTimeInterface;

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
        DateTimeInterface $created_at
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
