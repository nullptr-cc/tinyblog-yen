<?php

namespace TinyBlog\Web\Presenter\Base;

use TinyBlog\Web\WebRegistry;

abstract class BaseComponent
{
    protected $web;
    protected $components;
    protected $renderer;
    protected $authenticator;
    protected $settings;

    public function __construct(WebRegistry $web, ComponentRegistry $components)
    {
        $this->web = $web;
        $this->components = $components;
        $this->renderer = $web->getHtmlRenderer();
        $this->authenticator = $web->getUserAuthenticator();
        $this->settings = $web->getSettings();
    }

    public function __invoke(...$args)
    {
        return $this->present(...$args);
    }

    protected function component($cname)
    {
        return $this->components->getComponent('Component/' . $cname);
    }
}
