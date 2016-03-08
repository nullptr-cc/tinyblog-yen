<?php

namespace TinyBlog\Web\Presenter\Plugin;

class Escape
{
    public function __invoke($text)
    {
        return htmlspecialchars($text, ENT_COMPAT | ENT_HTML5);
    }
}
