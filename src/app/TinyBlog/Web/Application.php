<?php

namespace TinyBlog\Web;

use Yen\Http;
use Yen\Settings\Contract\ISettings;

class Application
{
    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function run()
    {
        $request = $this->createServerRequest();

        try {
            $fc = $this->createFrontController();
            $response = $fc->processRequest($request);
        } catch (\Exception $error) {
            $response = $this->createErrorResponse($error);
        };

        return $this->emitHttpResponse($response);
    }

    protected function emitHttpResponse(Http\Contract\IResponse $response)
    {
        while (ob_get_level()) {
            ob_end_clean();
        };

        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $content) {
            header(sprintf('%s: %s', $name, $content));
        };
        printf('%s', $response->getBody());
    }

    protected function createErrorResponse(\Exception $error)
    {
        $body = sprintf(
            "%s with '%s' in %s:%d\n%s",
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getTraceAsString()
        );

        return new \Yen\Http\Response(
            500,
            ['Content-Type' => 'text/plain;charset=utf-8'],
            $body
        );
    }

    protected function createDependencyContainer()
    {
        return new \TinyBlog\Core\DependencyContainer($this->settings);
    }

    protected function createFrontController()
    {
        $deps = $this->createDependencyContainer();

        return new \Yen\Core\FrontController(
            $deps->getRouter(),
            $deps->getHandlerRegistry()
        );
    }

    protected function createServerRequest()
    {
        return new Http\ServerRequest($_SERVER, $_GET, $_POST, $_COOKIE, Http\ServerRequest::fillFiles($_FILES));
    }
}
