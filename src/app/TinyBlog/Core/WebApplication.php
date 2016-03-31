<?php

namespace TinyBlog\Core;

use Yen\Http\Contract\IResponse;
use Yen\Http\ServerRequest;
use Yen\Http\Response;
use Yen\Settings\Contract\ISettings;
use Yen\Core\FrontController;
use Exception;

class WebApplication
{
    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function run()
    {
        $deps = $this->createDependencies();
        $request = $this->createServerRequest();

        try {
            $fc = $this->createFrontController($deps);
            $deps->getWeb()->getSession()->resume($request);
            $response = $fc->processRequest($request);
            $deps->getWeb()->getSession()->suspend();
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

    protected function createDependencies()
    {
        return new Dependencies($this->settings);
    }

    protected function createFrontController($deps)
    {
        return new FrontController(
            $deps->getWeb()->getRouter(),
            $deps->getWeb()->getHandlerRegistry()
        );
    }

    protected function createServerRequest()
    {
        return ServerRequest::createFromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }
}
