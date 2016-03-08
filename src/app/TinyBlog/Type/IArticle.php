<?php

namespace TinyBlog\Type;

use DateTimeInterface;

interface IArticle
{
    public function getId();
    public function getAuthor();
    public function getTitle();
    public function getBody();
    public function getTeaser();
    public function getCreatedAt();

    public function withId($id);
    public function withAuthor(IUser $author);
    public function withTitle($title);
    public function withBody(Content $body);
    public function withTeaser($teaser);
    public function withCreatedAt(DateTimeInterface $dt);
}
