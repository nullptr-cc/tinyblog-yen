<?php

namespace TinyBlog\Web\Presenter\Page;

use Yen\Presenter\Contract\IComponentRegistry;
use TinyBlog\Core\Contract\IDependencyContainer;
use TinyBlog\Web\Presenter\Base\CommonPage;

class Main extends CommonPage
{
    protected $url_builder;

    public function __construct(IDependencyContainer $dc, IComponentRegistry $components)
    {
        parent::__construct($dc, $components);
        $this->url_builder = $dc->getTools()->getUrlBuilder();
    }

    public function present(array $articles, array $paging)
    {
        $content = $this->getContent($articles, $paging);

        return $this->render($content);
    }

    protected function getContent(array $articles, array $paging)
    {
        if (!count($articles)) {
            return $this->renderer->render('page/empty_main', []);
        };

        $base_url = $this->url_builder->buildMainPageUrl();
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
