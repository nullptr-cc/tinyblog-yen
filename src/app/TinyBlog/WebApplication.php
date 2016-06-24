<?php

namespace TinyBlog;

use Yen\Http\Contract\IResponse;
use Yen\Http\ServerRequest;
use Yen\Http\Response;
use Yen\Settings\Contract\ISettings;
use Exception;

class WebApplication
{
    private $modules;

    public function __construct(ISettings $settings)
    {
        $this->modules = $this->createModules($settings);
    }

    public function run()
    {
        $request = $this->createServerRequest();
        $web = $this->modules->web();

        try {
            $web->getSession()->resume($request);
            $response = $web->getFrontController()->processRequest($request);
            $web->getSession()->suspend();
        } catch (Exception $error) {
            $response = $this->createErrorResponse($error);
        };

        return $this->emitHttpResponse($response);
    }

    private function emitHttpResponse(IResponse $response)
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

    private function createErrorResponse(Exception $error)
    {
        $body = sprintf(
            "%s with '%s' in %s:%d\n%s",
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getTraceAsString()
        );

        return Response::internalError()
                ->withHeader('Content-Type', 'text/plain;charset=utf-8')
                ->withBody($body);
    }

    protected function createModules(ISettings $settings)
    {
        return new Modules($settings);
    }

    protected function createServerRequest()
    {
        return ServerRequest::createFromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }
}
