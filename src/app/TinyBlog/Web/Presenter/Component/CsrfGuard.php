<?php

namespace TinyBlog\Web\Presenter\Component;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class CsrfGuard extends BaseComponent
{
    public function present()
    {
        $sentinel = $this->web->getSentinel();

        return $this->renderer->render(
            'component/csrf_guard',
            [
                'name' => $sentinel->getCsrfTokenName(),
                'value' => $sentinel->newCsrfToken()
            ]
        );
    }
}
