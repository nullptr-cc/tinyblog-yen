<?php

namespace TinyBlog\Web;

use Yen\Core\FrontController;
use Yen\ClassResolver\FormatClassResolver;
use Yen\ClassResolver\FallbackClassResolver;
use Yen\Handler\HandlerRegistry;
use Yen\Http\Uri;
use Yen\Renderer\HtmlTemplateRenderer;
use Yen\Renderer\JsonRenderer;
use Yen\Router\Router;
use Yen\Session\Session;
use Yen\Settings\Contract\ISettings;
use Yen\Util\LazyContainer;

use TinyBlog\Modules;
use TinyBlog\Web\Handler\HandlerFactory;
use TinyBlog\Web\Handler\NotFoundHandler;
use TinyBlog\Web\Presenter\Base\TemplatePresenter;
use TinyBlog\Web\Presenter\Base\ComponentRegistry;
use TinyBlog\Web\Presenter\Base\PluginRegistry;
use TinyBlog\Web\Service\UrlBuilder;

class ModuleWeb
{
    use LazyContainer;

    private $modules;
    private $settings;

    public function __construct(Modules $modules, ISettings $settings)
    {
        $this->modules = $modules;
        $this->settings = $settings;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getFrontController()
    {
        return $this->lazy('front_controller', [$this, 'makeFrontController']);
    }

    private function makeFrontController()
    {
        return new FrontController(
            $this->getRouter(),
            $this->getHandlerRegistry()
        );
    }

    public function getRouter()
    {
        return $this->lazy('router', [$this, 'makeRouter']);
    }

    private function makeRouter()
    {
        return Router::createFromRoutesFile($this->settings->get('routing_rules'));
    }

    public function getHandlerRegistry()
    {
        return $this->lazy('handler_registry', [$this, 'makeHandlerRegistry']);
    }

    private function makeHandlerRegistry()
    {
        $class_resolver = new FormatClassResolver(__NAMESPACE__ . '\\Handler\\Real\\%sHandler');
        $factory = new HandlerFactory($class_resolver, $this->modules);
        $registry = new HandlerRegistry($factory);
        $registry->setNotFoundHandler($factory->makeResolved(NotFoundHandler::class));

        return $registry;
    }

    public function getSession()
    {
        return $this->lazy('session', [$this, 'makeSession']);
    }

    private function makeSession()
    {
        return new Session();
    }

    public function getHtmlComponents()
    {
        return $this->lazy('html_components', [$this, 'makeHtmlComponents']);
    }

    private function makeHtmlComponents()
    {
        $resolver = new FormatClassResolver(__NAMESPACE__ . '\\Presenter\\%s');

        return new ComponentRegistry($this, $resolver);
    }

    public function getHtmlRenderer()
    {
        return $this->lazy('html_renderer', [$this, 'makeHtmlRenderer']);
    }

    private function makeHtmlRenderer()
    {
        return new HtmlTemplateRenderer(
            $this->settings->get('templates'),
            $this->makeRendererPluginRegistry()
        );
    }

    private function makeRendererPluginRegistry()
    {
        $resolver = new FormatClassResolver(__NAMESPACE__ . '\\Presenter\\Plugin\\%s');

        return new PluginRegistry($this, $resolver);
    }

    public function getJsonRenderer()
    {
        return $this->lazy('json_renderer', [$this, 'makeJsonRenderer']);
    }

    private function makeJsonRenderer()
    {
        return new JsonRenderer();
    }

    public function getUrlBuilder()
    {
        return $this->lazy('url_builder', [$this, 'makeUrlBuilder']);
    }

    private function makeUrlBuilder()
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

    private function makeUserAuthenticator()
    {
        return new Service\UserAuthenticator(
            $this->getSession(),
            $this->modules->user()->getUserRepo()
        );
    }

    public function getArticleDataValidator()
    {
        return new Service\ArticleDataValidator();
    }

    public function getArticleEditor()
    {
        return new Service\ArticleEditor(
            $this->modules->article()->getArticleRepo(),
            $this->modules->tools()->getMarkdownTransformer(),
            $this->modules->tools()->getTeaserMaker()
        );
    }

    public function getCommentDataValidator()
    {
        return new Service\CommentDataValidator();
    }

    public function getCommentEditor()
    {
        return new Service\CommentEditor(
            $this->modules->comment()->getCommentRepo()
        );
    }

    public function getSentinel()
    {
        return $this->lazy('sentinel', [$this, 'makeSentinel']);
    }

    private function makeSentinel()
    {
        return new Service\Sentinel(
            Uri::createFromString($this->settings->get('base_url'))->getHost()
        );
    }

    public function getHtmlResponder()
    {
        return $this->lazy('html_responder', [$this, 'makeHtmlResponder']);
    }

    private function makeHtmlResponder()
    {
        return new Responder\HtmlResponder(
            $this->getHtmlComponents()
        );
    }

    public function getJsonResponder()
    {
        return $this->lazy('json_responder', [$this, 'makeJsonResponder']);
    }

    private function makeJsonResponder()
    {
        return new Responder\JsonResponder(
            $this->getJsonRenderer()
        );
    }

    public function getRedirectResponder()
    {
        return new Responder\RedirectResponder();
    }
}
