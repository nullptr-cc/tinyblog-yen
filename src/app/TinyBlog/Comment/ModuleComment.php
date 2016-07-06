<?php

namespace TinyBlog\Comment;

use Yada\Driver as SqlDriver;
use TinyBlog\Comment\DataAccess\CommentFetcher;
use TinyBlog\Comment\DataAccess\CommentStore;

class ModuleComment
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getCommentRepo()
    {
        return new CommentRepo($this->getCommentStore(), $this->getCommentFetcher());
    }

    private function getCommentStore()
    {
        return new CommentStore($this->sql_driver);
    }

    private function getCommentFetcher()
    {
        return new CommentFetcher($this->sql_driver);
    }
}
