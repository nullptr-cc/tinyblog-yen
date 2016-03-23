<?php

namespace TinyBlog\Web\Presenter\Error;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class Forbidden extends BaseComponent
{
    public function present()
    {
        return $this->renderer->render('error/forbidden', []);
    }
}
