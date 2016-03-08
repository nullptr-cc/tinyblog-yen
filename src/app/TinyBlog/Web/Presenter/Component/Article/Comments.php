<?php

namespace TinyBlog\Web\Presenter\Component\Article;

use TinyBlog\Web\Presenter\Base\BaseComponent;
use TinyBlog\Type\IArticle;

class Comments extends BaseComponent
{
    public function present(IArticle $article)
    {
        return $this->renderer->render(
            'component/article/disqus_comments',
            [
                'siteid' => $this->settings->get('disqus.siteid'),
                'pageid' => 'article:' . $article->getId()
            ]
        );
    }
}
