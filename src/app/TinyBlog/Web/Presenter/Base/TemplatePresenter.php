<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Renderer\Contract\ITemplateRenderer;

class TemplatePresenter
{
    protected $renderer;
    protected $components;

    public function __construct(ITemplateRenderer $renderer, IComponents $components)
    {
        $this->renderer = $renderer;
        $this->components = $components;
    }

    public function present($cname, array $data = [])
    {
        $component = $this->components->getComponent($cname);
        return $component->present($data);
    }
}
