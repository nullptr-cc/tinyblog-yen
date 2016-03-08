<?php

namespace TinyBlog\Web\Presenter\Component;

use Yen\Http\Contract\IUri;
use TinyBlog\Web\Presenter\Base\BaseComponent;

class Paginator extends BaseComponent
{
    public function present(IUri $base_url, $page_num, $page_count)
    {
        $link_older = $link_newer = false;
        if ($page_num < $page_count) {
            $link_older = $base_url->withJoinedQuery(['page' => $page_num + 1]);
        };
        if ($page_num > 1) {
            $link_newer = $base_url->withJoinedQuery(['page' => $page_num - 1]);
        };

        return $this->renderer->render(
            'component/paginator',
            ['older' => $link_older, 'newer' => $link_newer]
        );
    }
}
