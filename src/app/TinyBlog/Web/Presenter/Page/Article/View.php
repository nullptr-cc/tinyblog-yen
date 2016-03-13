<?php

namespace TinyBlog\Web\Presenter\Page\Article;

use TinyBlog\Core\Contract\IDependencyContainer;
use TinyBlog\Web\Presenter\Base\CommonPage;
use TinyBlog\Type\IArticle;

class View extends CommonPage
{
    public function present(array $data)
    {
        return $this->render(
            $this->getContent($data['article']),
            $this->getPageTitle($data['article'])
        );
    }

    protected function getContent(IArticle $article)
    {
        $tools = '';
        $auth_user = $this->authenticator->getAuthUser();
        if ($auth_user && $auth_user->getId() == $article->author()->getId()) {
            $tools = $this->component('Article/Tools')->present($article);
        };
        $comments = $this->component('Article/Comments')->present($article);

        return $this->renderer->render(
            'page/article/view',
            [
                'article' => $article,
                'comments' => $comments,
                'article_tools' => $tools
            ]
        );
    }

    protected function getPageTitle(IArticle $article)
    {
        return $article->title() . ' :: TinyBlog';
    }
}
