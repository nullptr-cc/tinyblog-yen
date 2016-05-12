<?php

namespace TinyBlog\Web\Presenter\Page\Article;

use TinyBlog\Web\Presenter\Base\CommonPage;
use TinyBlog\Article\Article;

class Edit extends CommonPage
{
    public function present(array $data)
    {
        return $this->render(
            $this->getContent($data['article']),
            $this->getPageTitle()
        );
    }

    public function getContent(Article $article)
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
