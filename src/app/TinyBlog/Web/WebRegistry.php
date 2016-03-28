<?php

namespace TinyBlog\Web;

use Yen\Settings\Contract\ISettings;
use Yen\Router\Router;
use Yen\Util\LazyContainer;
use Yen\Util\FormatClassResolver;
use Yen\Session\Session;
use Yen\Renderer\HtmlTemplateRenderer;
use Yen\Renderer\JsonRenderer;
use Yen\Http\Uri;

use TinyBlog\Domain\DomainRegistry;
use TinyBlog\Tool\ToolRegistry;
use TinyBlog\Web\Handler\Base\HandlerRegistry;
use TinyBlog\Web\Handler\MissedHandler;
use TinyBlog\Web\Presenter\Base\TemplatePresenter;
use TinyBlog\Web\Presenter\Base\ComponentRegistry;
use TinyBlog\Web\Presenter\Base\PluginRegistry;
use TinyBlog\Web\Service\UrlBuilder;

class WebRegistry
{
    use LazyContainer;

    protected $settings;
    protected $domain;
    protected $tools;

    public function __construct(ISettings $settings, DomainRegistry $domain, ToolRegistry $tools)
    {
        $this->settings = $settings;
        $this->domain = $domain;
        $this->tools = $tools;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getRouter()
    {
        return $this->lazy('router', [$this, 'makeRouter']);
    }

    protected function makeRouter()
    {
        return Router::createFromRulesFile($this->settings->get('routing_rules'));
    }

    public function getHandlerRegistry()
    {
        return $this->lazy('handler_registry', [$this, 'makeHandlerRegistry']);
    }

    protected function makeHandlerRegistry()
    {
        $resolver = new FormatClassResolver(
            __NAMESPACE__ . '\\Handler\\Real\\%sHandler',
            MissedHandler::class
        );

        return new HandlerRegistry($this, $this->domain, $resolver);
    }

    public function getSession()
    {
        return $this->lazy('session', [$this, 'makeSession']);
    }

    protected function makeSession()
    {
        return new Session();
    }

    public function getHtmlComponents()
    {
        return $this->lazy('html_components', [$this, 'makeHtmlComponents']);
    }

    protected function makeHtmlComponents()
    {
        $resolver = new FormatClassResolver(__NAMESPACE__ . '\\Presenter\\%s');

        return new ComponentRegistry($this, $resolver);
    }

    public function getHtmlRenderer()
    {
        return $this->lazy('html_renderer', [$this, 'makeHtmlRenderer']);
    }

    protected function makeHtmlRenderer()
    {
        return new HtmlTemplateRenderer(
            $this->settings->get('templates'),
            $this->makeRendererPluginRegistry()
        );
    }

    protected function makeRendererPluginRegistry()
    {
        $resolver = new FormatClassResolver(__NAMESPACE__ . '\\Presenter\\Plugin\\%s');

        return new PluginRegistry($this, $resolver);
    }

    public function getJsonRenderer()
    {
        return $this->lazy('json_renderer', [$this, 'makeJsonRenderer']);
    }

    protected function makeJsonRenderer()
    {
        return new JsonRenderer();
    }

    public function getUrlBuilder()
    {
        return $this->lazy('url_builder', [$this, 'makeUrlBuilder']);
    }

    protected function makeUrlBuilder()
    {
        return new UrlBuilder(
            $this->getRouter(),
            Uri::createFromString($this->settings->get('base_url'))
        );
    }

    public function getUserAuthenticator()
    {
        return $this->lazy('user_authenticator', [$this, 'makeUserAuthenticator']);
    }

    protected function makeUserAuthenticator()
    {
        return new Service\UserAuthenticator(
            $this->getSession(),
            $this->domain->getUserFinder()
        );
    }

    public function getArticleDataValidator()
    {
        return new Service\ArticleDataValidator();
    }

    public function getArticleEditor()
    {
        return new Service\ArticleEditor(
            $this->domain->getArticleRepo(),
            $this->tools->getMarkdownTransformer(),
            $this->tools->getTeaserMaker()
        );
    }

    public function getCommentDataValidator()
    {
        return new Service\CommentDataValidator();
    }

    public function getCommentEditor()
    {
        return new Service\CommentEditor($this->domain->getCommentRepo());
    }
}
