<?php

namespace TinyBlog\Web\Presenter\Base;

class TemplatePresenter extends \Yen\Presenter\TemplatePresenter
{
    public function errorNotFound()
    {
        $content = $this->render('Error/NotFound', []);
        return parent::errorNotFound()->withBody($content);
    }
}
