<?php

namespace TinyBlog\Web\Presenter\Page;

use TinyBlog\Web\Presenter\Base\CommonPage;

class Main extends CommonPage
{
    public function present(array $data)
    {
        $articles = $data['articles'];
        $paging = $data['paging'];
        $content = $this->getContent($articles, $paging);

        return $this->render($content);
    }

    protected function getContent(array $articles, array $paging)
    {
        if (!count($articles)) {
            return $this->renderer->render('page/empty_main', []);
        };

        $base_url = $this->web->getUrlBuilder()->buildMainPageUrl();
        $paginator = $this->component('Paginator');

        return $this->renderer->render(
            'page/main',
            [
                'articles' => $articles,
                'paginator' => $paginator($base_url, $paging['num'], $paging['count'])
            ]
        );
    }
}
