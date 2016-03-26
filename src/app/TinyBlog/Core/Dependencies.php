<?php

namespace TinyBlog\Core;

use Yen\Util\LazyContainer;
use Yen\Settings\Contract\ISettings;
use TinyBlog\DataAccess\DataAccessRegistry;
use TinyBlog\Domain\DomainRegistry;
use TinyBlog\Web\WebRegistry;
use TinyBlog\Tool\ToolRegistry;

class Dependencies
{
    use LazyContainer;

    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function getDataAccess()
    {
        return $this->lazy('data_access', [$this, 'makeDataAccess']);
    }

    public function getDomain()
    {
        return $this->lazy('domain', [$this, 'makeDomain']);
    }

    public function getWeb()
    {
        return $this->lazy('web', [$this, 'makeWeb']);
    }

    public function getTools()
    {
        return $this->lazy('tools', [$this, 'makeTools']);
    }

    protected function makeDataAccess()
    {
        return new DataAccessRegistry(
            $this->settings->get('db')
        );
    }

    protected function makeDomain()
    {
        return new DomainRegistry(
            $this->getDataAccess(),
            $this->getTools()
        );
    }

    protected function makeWeb()
    {
        return new WebRegistry(
            $this->settings->get('web'),
            $this->getDomain(),
            $this->getTools()
        );
    }

    protected function makeTools()
    {
        return new ToolRegistry();
    }
}
