<?php

namespace TinyBlog\Web\Presenter\Component\Article;

use TinyBlog\Web\Presenter\Base\BaseComponent;

class Comments extends BaseComponent
{
    public function present(array $comments)
    {
        return $this->renderer->render(
            'component/article/comments',
            [
                'comments' => $comments
            ]
        );
    }
}
