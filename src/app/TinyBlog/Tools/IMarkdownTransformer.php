<?php

namespace TinyBlog\Tools;

interface IMarkdownTransformer
{
    public function toHtml($md_text);
}
