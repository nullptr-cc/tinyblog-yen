<?php

namespace TinyBlog\Type;

use DateTimeInterface;

interface IArticleInitData
{
    public function getArticleId();
    public function getTitle();
    public function getBody();
    public function getCreatedAt();

    public function withCreatedAt(DateTimeInterface $created_at);
}
