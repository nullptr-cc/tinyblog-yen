<?php

namespace TinyBlog\User;

final class UserRole
{
    const GUEST = 0;
    const CONSUMER = 1;
    const AUTHOR = 2;

    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function GUEST()
    {
        return new self(self::GUEST);
    }

    public static function CONSUMER()
    {
        return new self(self::CONSUMER);
    }

    public static function AUTHOR()
    {
        return new self(self::AUTHOR);
    }

    public static function fromValue($value)
    {
        switch ($value) {
            case self::GUEST:
                return self::GUEST();
                break;
            case self::CONSUMER:
                return self::CONSUMER();
                break;
            case self::AUTHOR:
                return self::AUTHOR();
                break;
            default:
                throw new \InvalidArgumentException('Unknown UserRole value: ' . $value);
                break;
        };
    }

    public function value()
    {
        return $this->value;
    }
}
