<?php

namespace TinyBlog\Comment;

use Yada\Driver;

class ModuleComment
{
    protected $sql_driver;

    public function __construct(Driver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function getCommentRepo()
    {
        return new CommentRepo($this->getCommentStore(), $this->getCommentFetcher());
    }

    private function getCommentStore()
    {
        return new DataAccess\CommentStore($this->sql_driver);
    }

    private function getCommentFetcher()
    {
        return new DataAccess\CommentFetcher($this->sql_driver);
    }
}
