<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;

class ArticleListData
{
    protected $page_num;

    public function __construct($page_num)
    {
        $this->page_num = $page_num;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $page_num = 1;
        if (array_key_exists('page', $request->getQueryParams())) {
            $page_num = intval($request->getQueryParams()['page']);
        };

        return new self($page_num);
    }

    public function getPageNum()
    {
        return $this->page_num;
    }
}
