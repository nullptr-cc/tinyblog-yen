<?php

namespace TinyBlog\Domain\Validator;

class Result
{
    protected $valid;
    protected $errors;

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
