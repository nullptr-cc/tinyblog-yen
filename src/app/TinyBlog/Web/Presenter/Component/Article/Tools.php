<?php

namespace TinyBlog\Web\Presenter\Component\Article;

use TinyBlog\Web\Presenter\Base\BaseComponent;
use TinyBlog\Type\Article;

class Tools extends BaseComponent
{
    public function present(Article $article)
    {
        return $this->renderer->render(
            'page/article/_tools',
            ['article' => $article]
        );
    }
}
