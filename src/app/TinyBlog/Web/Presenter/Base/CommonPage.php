<?php

namespace TinyBlog\Web\Presenter\Base;

abstract class CommonPage extends BaseComponent
{
    protected function render($content, $title = null)
    {
        $title = $title ?: $this->getDefaultPageTitle();
        $main_menu = $this->component('MainMenu');

        return $this->renderer->render(
            'layout/common',
            [
                'content' => $content,
                'main_menu' => $main_menu(),
                'page_title' => $title,
                'base_url' => $this->web->getSettings()->get('base_url')
            ]
        );
    }

    protected function getDefaultPageTitle()
    {
        return 'Tiny Blog - sample application on Yen web-framework';
    }
}
