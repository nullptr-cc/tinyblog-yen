<?php

namespace TinyBlog\Tools;

class ModuleTools
{
    public function __construct()
    {
    }

    public function getMarkdownTransformer()
    {
        return new MichelfMarkdownTransformer(new \Michelf\Markdown());
    }

    public function getTeaserMaker()
    {
        return new TeaserMaker();
    }

    public function getHttpClient()
    {
        return new \Yen\HttpClient\CurlHttpClient([
            'connect_timeout' => 2,
            'timeout' => 4
        ]);
    }
}
