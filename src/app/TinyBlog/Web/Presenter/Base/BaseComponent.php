<?php

namespace TinyBlog\Web\Presenter\Base;

use TinyBlog\Core\Contract\IDependencyContainer;

abstract class BaseComponent
{
    protected $renderer;
    protected $components;
    protected $authenticator;
    protected $settings;

    public function __construct(IDependencyContainer $dc, IComponents $components)
    {
        $this->renderer = $dc->getHtmlRenderer();
        $this->components = $components;
        $this->authenticator = $dc->getUserAuthenticator();
        $this->settings = $dc->getSettings();
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
