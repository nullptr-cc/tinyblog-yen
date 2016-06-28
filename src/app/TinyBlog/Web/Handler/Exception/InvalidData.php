<?php

namespace TinyBlog\Web\Handler\Exception;

class InvalidData extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct();
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
