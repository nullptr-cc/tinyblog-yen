<?php

namespace TinyBlog\Tools;

use TinyBlog\Tools\Contract\IMarkdownTransformer;
use Michelf\MarkdownInterface;

class MichelfMarkdownTransformer implements IMarkdownTransformer
{
    protected $lib;

    public function __construct(MarkdownInterface $lib)
    {
        $this->lib = $lib;
    }

    public function toHtml($md_text)
    {
        return $this->lib->transform($md_text);
    }
}
