<?php

namespace TinyBlog\Web\Presenter\Component;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class MainMenu extends BaseComponent
{
    public function present($active_item = false)
    {
        $in_out = $this->component('MainMenu/InOut');
        $create_item = '';
        if ($this->authenticator->getAuthUser()) {
            $create_item = $this->component('MainMenu/Create')->present();
        };

        return $this->renderer->render(
            'component/main_menu/main_menu',
            [
                'active_item' => $active_item,
                'in_out' => $in_out(),
                'create_item' => $create_item
            ]
        );
    }
}
