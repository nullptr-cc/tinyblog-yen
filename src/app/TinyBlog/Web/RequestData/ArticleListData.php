<?php

namespace TinyBlog\Web\RequestData;

use Yen\Http\Contract\IServerRequest;
use Yen\Util\Extractor;

class ArticleListData
{
    protected $page_num;

    public function __construct($page_num)
    {
        $this->page_num = $page_num;
    }

    public static function createFromRequest(IServerRequest $request)
    {
        $page_num = Extractor::extractInt($request->getQueryParams(), 'page', 1);

        return new self($page_num);
    }

    public function getPageNum()
    {
        return $this->page_num;
    }
}
