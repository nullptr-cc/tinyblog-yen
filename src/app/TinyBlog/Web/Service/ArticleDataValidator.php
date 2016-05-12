<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Validation\ValidationResult;

class ArticleDataValidator
{
    public function validate(ArticleData $data)
    {
        $errors = [];

        if (trim($data->getTitle()) == '') {
            $errors['title'] = 'empty title';
        };

        if (trim($data->getBody()) == '') {
            $errors['body'] = 'empty body';
        };

        return new ValidationResult(!count($errors), $errors);
    }
}
