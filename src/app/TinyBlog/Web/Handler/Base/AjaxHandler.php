<?php

namespace TinyBlog\Web\Handler\Base;

use TinyBlog\Core\Contract\IDependencyContainer;

abstract class AjaxHandler extends BaseHandler
{
    protected $json_presenter;

    public function __construct(IDependencyContainer $dc)
    {
        parent::__construct($dc);
        $this->json_presenter = $dc->getJsonPresenter();
    }

    protected function getPresenter()
    {
        return $this->json_presenter;
    }

    protected function getErrorPresenter()
    {
        return $this->json_presenter;
    }
}
