<?php

namespace TinyBlog\Web\Presenter\Component\MainMenu;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class Create extends BaseComponent
{
    public function present()
    {
        return $this->renderer->render('component/main_menu/create', []);
    }
}
