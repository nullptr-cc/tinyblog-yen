<?php

namespace TinyBlog\Domain\Comment;

use TinyBlog\DataAccess\Comment\CommentSaver;
use TinyBlog\Type\Comment;

class CommentRepo
{
    protected $saver;

    public function __construct(CommentSaver $saver)
    {
        $this->saver = $saver;
    }

    public function persistComment(Comment $comment)
    {
        if ($comment->getId() != 0) {
            $this->saver->updateComment($comment);
            return $comment;
        };

        $result = $this->saver->insertComment($comment);
        return $comment->withId($result->id);
    }

    public function deleteComment(Comment $comment)
    {
        return $this->saver->deleteComment($comment);
    }
}
