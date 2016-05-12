<?php

namespace TinyBlog\Web\Presenter\Page\Article;

use TinyBlog\Core\Contract\IDependencyContainer;
use TinyBlog\Web\Presenter\Base\CommonPage;
use TinyBlog\Article\Article;
use TinyBlog\User\User;

class View extends CommonPage
{
    public function present(array $data)
    {
        return $this->render(
            $this->getContent($data['article'], $data['comments']),
            $this->getPageTitle($data['article'])
        );
    }

    protected function getContent(Article $article, array $comments)
    {
        $auth_user = $this->authenticator->getAuthUser();
        $comments = $this->component('Article/Comments')->present($comments);
        $tools = $cform = '';

        if ($auth_user->getId() == $article->author()->getId()) {
            $tools = $this->component('Article/Tools')->present($article);
        };

        if ($auth_user->getRole() >= User::ROLE_CONSUMER) {
            $cform = $this->renderer->render(
                'component/article/comment_form',
                ['article' => $article]
            );
        };

        return $this->renderer->render(
            'page/article/view',
            [
                'article' => $article,
                'article_tools' => $tools,
                'comments' => $comments,
                'comment_form' => $cform
            ]
        );
    }

    protected function getPageTitle(Article $article)
    {
        return $article->title() . ' :: TinyBlog';
    }
}
