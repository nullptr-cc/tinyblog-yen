<?php

namespace TinyBlog\Validation;

class ValidationResult
{
    private $valid;
    private $errors;

    public function __construct($valid, array $errors = [])
    {
        $this->valid = $valid;
        $this->errors = $errors;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
