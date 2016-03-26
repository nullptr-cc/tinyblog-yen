<?php

namespace TinyBlog\Tool;

class ToolRegistry
{
    public function __construct()
    {
    }

    public function getMarkdownTransformer()
    {
        return new MarkdownTransformer();
    }

    public function getTeaserMaker()
    {
        return new TeaserMaker();
    }
}
