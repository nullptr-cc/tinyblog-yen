<?php

namespace TinyBlog\Web\Presenter\Component\Article;

use TinyBlog\Web\Presenter\Base\BaseComponent;
use TinyBlog\Type\Article;

class Comments extends BaseComponent
{
    public function present(Article $article)
    {
        return $this->renderer->render(
            'component/article/disqus_comments',
            [
                'siteid' => $this->settings->get('disqus_siteid'),
                'pageid' => 'article:' . $article->getId()
            ]
        );
    }
}
