<?php

namespace TinyBlog\Web\Service;

use TinyBlog\Web\RequestData\CommentData;
use TinyBlog\Type\ValidationResult;

class CommentDataValidator
{
    public function validate(CommentData $data)
    {
        $errors = [];

        if (trim($data->getBody()) == '') {
            $errors['body'] = 'empty body';
        };

        return new ValidationResult(!count($errors), $errors);
    }
}
