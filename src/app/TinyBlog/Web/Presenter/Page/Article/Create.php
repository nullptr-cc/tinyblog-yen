<?php

namespace TinyBlog\Web\Presenter\Page\Article;

use TinyBlog\Web\Presenter\Base\CommonPage;
use TinyBlog\Type\Article;

class Create extends CommonPage
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
            'page/article/create',
            ['article' => $article]
        );
    }

    protected function getPageTitle()
    {
        return 'Write new article :: TinyBlog';
    }
}
