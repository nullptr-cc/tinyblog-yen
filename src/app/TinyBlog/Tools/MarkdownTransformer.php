<?php

namespace TinyBlog\Tools;

use Michelf\Markdown;

class MarkdownTransformer
{
    public function toHtml($md_text)
    {
        return Markdown::defaultTransform($md_text);
    }
}