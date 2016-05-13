<?php

namespace TinyBlog\Comment;

use TinyBlog\Article\Article;
use TinyBlog\User\User;
use DateTimeInterface;

class Comment
{
    protected $id;
    protected $article;
    protected $author;
    protected $body;
    protected $created_at;

    public function __construct(array $init_data = [])
    {
        if (isset($init_data['id'])) {
            $this->setId($init_data['id']);
        };

        if (isset($init_data['article'])) {
            $this->setArticle($init_data['article']);
        } else {
            $this->setArticle(new Article());
        };

        if (isset($init_data['author'])) {
            $this->setAuthor($init_data['author']);
        } else {
            $this->setAuthor(new User());
        };

        if (isset($init_data['body'])) {
            $this->setBody($init_data['body']);
        } else {
            $this->setBody('');
        };

        if (isset($init_data['created_at'])) {
            $this->setCreatedAt($init_data['created_at']);
        };
    }

    protected function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    protected function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    protected function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    protected function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    protected function setCreatedAt(DateTimeInterface $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function withId($id)
    {
        $clone = clone $this;
        $clone->setId($id);
        return $clone;
    }

    public function withArticle(Article $article)
    {
        $clone = clone $this;
        $clone->setArticle($article);
        return $clone;
    }

    public function withAuthor(User $author)
    {
        $clone = clone $this;
        $clone->setAuthor($author);
        return $clone;
    }

    public function withBody($body)
    {
        $clone = clone $this;
        $clone->setBody($body);
        return $clone;
    }

    public function withCreatedAt(DateTimeInterface $created_at)
    {
        $clone = clone $this;
        $clone->setCreatedAt($created_at);
        return $clone;
    }

    public function id()
    {
        return $this->getId();
    }

    public function article()
    {
        return $this->getArticle();
    }

    public function author()
    {
        return $this->getAuthor();
    }

    public function body()
    {
        return $this->getBody();
    }

    public function created()
    {
        return $this->getCreatedAt();
    }
}
