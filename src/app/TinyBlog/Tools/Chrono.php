<?php

namespace TinyBlog\Tools;

use DateTimeImmutable;

class Chrono
{
    public function now()
    {
        return new DateTimeImmutable();
    }
}
