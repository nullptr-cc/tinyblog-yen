<?php

namespace TinyBlog\Type;

class Content
{
    protected $source;
    protected $html;

    public function __construct($source = '', $html = '')
    {
        $this->source = $source;
        $this->html = $html;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function source()
    {
        return $this->getSource();
    }

    public function html()
    {
        return $this->getHtml();
    }
}
