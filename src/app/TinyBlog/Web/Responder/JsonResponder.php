<?php

namespace TinyBlog\Web\Responder;

use Yen\Renderer\JsonRenderer;
use Yen\Http\Response;

class JsonResponder
{
    private $json_renderer;

    public function __construct(JsonRenderer $json_renderer)
    {
        $this->json_renderer = $json_renderer;
    }

    public function ok($data)
    {
        $doc = $this->json_renderer->render($data);

        return Response::ok()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function error($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);

        return Response::internalError()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function notFound($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);

        return Response::notFound()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function badParams($data)
    {
        $doc = $this->json_renderer->render($data);

        return Response::badRequest()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function forbidden($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);

        return Response::forbidden()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }
}
