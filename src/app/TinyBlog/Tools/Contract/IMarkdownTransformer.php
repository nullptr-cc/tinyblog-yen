<?php

namespace TinyBlog\Tools\Contract;

interface IMarkdownTransformer
{
    public function toHtml($md_text);
}
