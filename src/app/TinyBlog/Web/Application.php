<?php

namespace TinyBlog\Web;

use Yen\Http\Contract\IResponse;
use Yen\Http\ServerRequest;
use Yen\Http\Response;
use Yen\Settings\Contract\ISettings;
use Exception;

class Application
{
    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function run()
    {
        $deps = $this->createDependencyContainer();
        $request = $this->createServerRequest();

        try {
            $fc = $this->createFrontController($deps);
            $deps->getSession()->resume($request);
            $response = $fc->processRequest($request);
            $deps->getSession()->suspend();
        } catch (Exception $error) {
            $response = $this->createErrorResponse($error);
        };

        return $this->emitHttpResponse($response);
    }

    protected function emitHttpResponse(IResponse $response)
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

    protected function createErrorResponse(Exception $error)
    {
        $body = sprintf(
            "%s with '%s' in %s:%d\n%s",
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getTraceAsString()
        );

        return new Response(
            500,
            ['Content-Type' => 'text/plain;charset=utf-8'],
            $body
        );
    }

    protected function createDependencyContainer()
    {
        return new \TinyBlog\Core\DependencyContainer($this->settings);
    }

    protected function createFrontController($deps)
    {
        return new \Yen\Core\FrontController(
            $deps->getRouter(),
            $deps->getHandlerRegistry()
        );
    }

    protected function createServerRequest()
    {
        return new ServerRequest($_SERVER, $_GET, $_POST, $_COOKIE, ServerRequest::fillFiles($_FILES));
    }
}
