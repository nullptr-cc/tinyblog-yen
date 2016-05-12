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
        return $this->with(['id' => $id]);
    }

    public function withArticle(Article $article)
    {
        return $this->with(['article' => $article]);
    }

    public function withAuthor(User $author)
    {
        return $this->with(['author' => $author]);
    }

    public function withBody($body)
    {
        return $this->with(['body' => $body]);
    }

    public function withCreatedAt(DateTimeInterface $created_at)
    {
        return $this->with(['created_at' => $created_at]);
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

    protected function with(array $replacement)
    {
        return new self(
            array_merge($this->parts(), $replacement)
        );
    }

    protected function parts()
    {
        return [
            'id' => $this->id,
            'article' => $this->article,
            'author' => $this->author,
            'body' => $this->body,
            'created_at' => $this->created_at
        ];
    }
}
