<?php

namespace TinyBlog\Web\Presenter\Page\Auth;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class Entrance extends BaseComponent
{
    public function present()
    {
        return $this->renderer->render(
            'page/auth/entrance',
            [
                'base_url' => $this->web->getSettings()->get('base_url'),
                'csrf_guard' => $this->component('CsrfGuard')->present()
            ]
        );
    }
}
