<?php

namespace TinyBlog\Domain;

use TinyBlog\DataAccess\DataAccessRegistry;
use TinyBlog\Tool\ToolRegistry;
use TinyBlog\Domain\Article\ArticleFinder;
use TinyBlog\Domain\Article\ArticleRepo;
use TinyBlog\Domain\User\UserFinder;
use TinyBlog\Domain\Comment\CommentFinder;
use TinyBlog\Domain\Comment\CommentRepo;

class DomainRegistry
{
    protected $dar;
    protected $tools;

    public function __construct(DataAccessRegistry $dar, ToolRegistry $tools)
    {
        $this->dar = $dar;
        $this->tools = $tools;
    }

    public function getArticleFinder()
    {
        return new ArticleFinder($this->dar);
    }

    public function getUserFinder()
    {
        return new UserFinder($this->dar);
    }

    public function getArticleRepo()
    {
        return new ArticleRepo($this->dar->getArticleSaver());
    }

    public function getCommentFinder()
    {
        return new CommentFinder($this->dar);
    }

    public function getCommentRepo()
    {
        return new CommentRepo($this->dar->getCommentSaver());
    }
}
