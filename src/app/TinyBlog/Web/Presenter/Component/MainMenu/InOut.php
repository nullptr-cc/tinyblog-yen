<?php

namespace TinyBlog\Web\Presenter\Component\MainMenu;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class InOut extends BaseComponent
{
    public function present()
    {
        $user = $this->authenticator->getAuthUser();

        if (!$user) {
            return $this->renderer->render('component/main_menu/in_out/in', []);
        };

        return $this->renderer->render(
            'component/main_menu/in_out/out',
            ['user' => $user]
        );
    }
}
