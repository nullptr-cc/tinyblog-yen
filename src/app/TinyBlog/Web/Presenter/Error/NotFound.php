<?php

namespace TinyBlog\Web\Presenter\Error;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class NotFound extends BaseComponent
{
    public function present()
    {
        return $this->renderer->render('error/not_found', []);
    }
}
