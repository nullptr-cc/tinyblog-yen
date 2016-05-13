<?php

namespace TinyBlog\Article;

use TinyBlog\User\User;
use DateTimeInterface;

class Article
{
    protected $id;
    protected $author;
    protected $title;
    protected $body;
    protected $teaser;
    protected $created_at;

    public function __construct(array $init_data = [])
    {
        if (isset($init_data['id'])) {
            $this->setId($init_data['id']);
        };

        if (isset($init_data['author'])) {
            $this->setAuthor($init_data['author']);
        } else {
            $this->setAuthor(new User());
        };

        if (isset($init_data['title'])) {
            $this->setTitle($init_data['title']);
        };

        if (isset($init_data['body'])) {
            $this->setBody($init_data['body']);
        } else {
            $this->setBody(new Content());
        };

        if (isset($init_data['teaser'])) {
            $this->setTeaser($init_data['teaser']);
        };

        if (isset($init_data['created_at'])) {
            $this->setCreatedAt($init_data['created_at']);
        };
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getTeaser()
    {
        return $this->teaser;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    protected function setId($id)
    {
        $this->id = intval($id);
    }

    protected function setAuthor(User $author)
    {
        $this->author = $author;
    }

    protected function setTitle($title)
    {
        $this->title = $title;
    }

    protected function setBody(Content $body)
    {
        $this->body = $body;
    }

    protected function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

    protected function setCreatedAt(DateTimeInterface $dt)
    {
        $this->created_at = $dt;
    }

    public function withId($id)
    {
        $clone = clone $this;
        $clone->setId($id);
        return $clone;
    }

    public function withAuthor(User $author)
    {
        $clone = clone $this;
        $clone->setAuthor($author);
        return $clone;
    }

    public function withTitle($title)
    {
        $clone = clone $this;
        $clone->setTitle($title);
        return $clone;
    }

    public function withBody(Content $body)
    {
        $clone = clone $this;
        $clone->setBody($body);
        return $clone;
    }

    public function withTeaser($teaser)
    {
        $clone = clone $this;
        $clone->setTeaser($teaser);
        return $clone;
    }

    public function withCreatedAt(DateTimeInterface $dt)
    {
        $clone = clone $this;
        $clone->setCreatedAt($dt);
        return $clone;
    }

    public function id()
    {
        return $this->id;
    }

    public function title()
    {
        return $this->title;
    }

    public function body()
    {
        return $this->body;
    }

    public function author()
    {
        return $this->author;
    }

    public function teaser()
    {
        return $this->teaser;
    }

    public function created()
    {
        return $this->created_at;
    }
}
