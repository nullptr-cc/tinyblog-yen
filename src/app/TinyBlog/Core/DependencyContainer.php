<?php

namespace TinyBlog\Core;

use Yen\Settings\Contract\ISettings;
use Yen\Util\LazyContainer;

class DependencyContainer implements Contract\IDependencyContainer
{
    use LazyContainer;

    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getRouter()
    {
        return $this->lazy('router', [$this, 'makeRouter']);
    }

    public function getHandlerRegistry()
    {
        return $this->lazy('handler_registry', [$this, 'makeHandlerRegistry']);
    }

    public function getHtmlRenderer()
    {
        return $this->lazy('html_renderer', [$this, 'makeHtmlRenderer']);
    }

    public function getHtmlPresenter()
    {
        return $this->lazy('html_presenter', [$this, 'makeHtmlPresenter']);
    }

    public function getJsonPresenter()
    {
        return $this->lazy('json_presenter', [$this, 'makeJsonPresenter']);
    }

    public function getJsonRenderer()
    {
        return $this->lazy('json_renderer', [$this, 'makeJsonRenderer']);
    }

    public function getDataAccessRegistry()
    {
        return $this->lazy('data_access_registry', [$this, 'makeDataAccessRegistry']);
    }

    public function getDomainRegistry()
    {
        return $this->lazy('domain_registry', [$this, 'makeDomainRegistry']);
    }

    public function getValidators()
    {
        return $this->lazy('validators', [$this, 'makeValidators']);
    }

    public function getTools()
    {
        return $this->lazy('tools', [$this, 'makeTools']);
    }

    public function getSession()
    {
        return $this->lazy('session', [$this, 'makeSession']);
    }

    public function getUserAuthenticator()
    {
        return $this->lazy('user_authenticator', [$this, 'makeUserAuthenticator']);
    }

    protected function makeRouter()
    {
        return \Yen\Router\Router::createFromRulesFile($this->settings->get('routing_rules'));
    }

    protected function makeHandlerRegistry()
    {
        $resolver = new \Yen\Util\FormatClassResolver(
            '\\TinyBlog\\Web\\Handler\\Real\\%sHandler',
            \TinyBlog\Web\Handler\MissedHandler::class
        );

        return new \TinyBlog\Web\Handler\Base\HandlerRegistry($this, $resolver);
    }

    protected function makeRendererPluginRegistry()
    {
        $resolver = new \Yen\Util\FormatClassResolver('\\TinyBlog\\Web\\Presenter\\Plugin\\%s');

        return new \TinyBlog\Web\Presenter\Base\PluginRegistry($this, $resolver);
    }

    protected function makeHtmlRenderer()
    {
        return new \Yen\Renderer\HtmlTemplateRenderer(
            $this->settings->get('templates'),
            $this->makeRendererPluginRegistry()
        );
    }

    protected function makeHtmlPresenter()
    {
        $resolver = new \Yen\Util\FormatClassResolver(
            '\\TinyBlog\\Web\\Presenter\\%s'
        );

        return new \TinyBlog\Web\Presenter\Base\TemplatePresenter(
            $this->getHtmlRenderer(),
            new \TinyBlog\Web\Presenter\Base\ComponentRegistry($this, $resolver)
        );
    }

    protected function makeJsonPresenter()
    {
        return new \Yen\Presenter\DataPresenter(
            new \Yen\Renderer\JsonRenderer()
        );
    }

    protected function makeJsonRenderer()
    {
        return new \Yen\Renderer\JsonRenderer();
    }

    protected function makeSqlDriver()
    {
        return new \Yada\Driver(
            $this->settings->get('db.dsn'),
            $this->settings->get('db.username'),
            $this->settings->get('db.password')
        );
    }

    protected function makeDataAccessRegistry()
    {
        return new \TinyBlog\DataAccess\DataAccessRegistry(
            $this->makeSqlDriver()
        );
    }

    protected function makeDomainRegistry()
    {
        return new \TinyBlog\Domain\DomainRegistry($this);
    }

    protected function makeValidators()
    {
        return new \TinyBlog\Domain\Validator\ValidatorFactory();
    }

    protected function makeTools()
    {
        return new \TinyBlog\Tool\ToolRegistry($this, $this->settings);
    }

    protected function makeSession()
    {
        return new \Yen\Session\Session();
    }

    protected function makeUserAuthenticator()
    {
        return new \TinyBlog\Web\Service\UserAuthenticator(
            $this->getSession(),
            $this->getDomainRegistry()->getUserFinder()
        );
    }
}
