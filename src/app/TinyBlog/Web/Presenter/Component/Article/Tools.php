<?php

namespace TinyBlog\Web\Presenter\Component\Article;

use TinyBlog\Web\Presenter\Base\BaseComponent;
use TinyBlog\Article\Article;

class Tools extends BaseComponent
{
    public function present(Article $article)
    {
        return $this->renderer->render(
            'page/article/_tools',
            [
                'article' => $article,
                'csrf_guard' => $this->component('CsrfGuard')->present()
            ]
        );
    }
}
