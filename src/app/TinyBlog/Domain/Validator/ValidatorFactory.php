<?php

namespace TinyBlog\Domain\Validator;

class ValidatorFactory
{
    public function getArticleValidator()
    {
        return new ArticleValidator();
    }
}
