<?php

namespace TinyBlog\Web\Presenter\Page\Article;

use TinyBlog\Web\Presenter\Base\CommonPage;
use TinyBlog\Type\IArticle;

class Edit extends CommonPage
{
    public function present(IArticle $article)
    {
        return $this->render(
            $this->getContent($article),
            $this->getPageTitle()
        );
    }

    public function getContent(IArticle $article)
    {
        return $this->renderer->render(
            'page/article/edit',
            ['article' => $article]
        );
    }

    protected function getPageTitle()
    {
        return 'Edit article :: TinyBlog';
    }
}
