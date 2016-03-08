<?php

namespace TinyBlog\Web\Presenter\Page\Auth;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class Entrance extends BaseComponent
{
    public function present()
    {
        return $this->renderer->render('page/auth/entrance', []);
    }
}
