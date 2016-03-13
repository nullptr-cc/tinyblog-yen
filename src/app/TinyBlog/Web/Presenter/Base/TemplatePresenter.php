<?php

namespace TinyBlog\Web\Presenter\Base;

use Yen\Renderer\Contract\ITemplateRenderer;

class TemplatePresenter extends \Yen\Presenter\TemplatePresenter
{
    protected $components;

    public function __construct(ITemplateRenderer $renderer, IComponents $components)
    {
        parent::__construct($renderer);
        $this->components = $components;
    }

    public function errorNotFound()
    {
        $content = $this->render('Error/NotFound', []);
        return parent::errorNotFound()->withBody($content);
    }

    protected function render($cname, array $params)
    {
        return $this->components->getComponent($cname)->present($params);
    }
}
