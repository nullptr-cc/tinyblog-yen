<?php

namespace TinyBlog\Domain\Validator;

use TinyBlog\Type\IArticleInitData;

class ArticleValidator
{
    public function validate(IArticleInitData $data)
    {
        $errors = [];

        if (trim($data->getTitle()) == '') {
            $errors['title'] = 'empty title';
        };

        if (trim($data->getBody()) == '') {
            $errors['body'] = 'empty body';
        };

        return new Result(!count($errors), $errors);
    }
}
