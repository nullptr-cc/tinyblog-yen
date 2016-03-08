<?php

namespace TinyBlog\Tool;

use Yen\Http\Uri;
use Yen\Settings\Contract\ISettings;
use Yen\Util\LazyContainer;
use TinyBlog\Core\Contract\IDependencyContainer;

class ToolRegistry
{
    use LazyContainer;

    protected $dc;
    protected $settings;

    public function __construct(IDependencyContainer $dc, ISettings $settings)
    {
        $this->dc = $dc;
        $this->settings = $settings;
    }

    public function getUrlBuilder()
    {
        return $this->lazy('url_builder', [$this, 'makeUrlBuilder']);
    }

    public function getMarkdownTransformer()
    {
        return new MarkdownTransformer();
    }

    public function getTeaserMaker()
    {
        return new TeaserMaker();
    }

    protected function makeUrlBuilder()
    {
        return new UrlBuilder(
            $this->dc->getRouter(),
            Uri::createFromString($this->settings->get('base_url'))
        );
    }
}
