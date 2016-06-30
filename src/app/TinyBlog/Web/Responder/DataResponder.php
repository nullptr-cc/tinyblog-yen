<?php

namespace TinyBlog\Web\Responder;

use Yen\Renderer\Contract\IDataRenderer;
use Yen\Http\Response;

class DataResponder
{
    private $renderer;

    public function __construct(IDataRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function ok($data)
    {
        $doc = $this->renderer->render($data);

        return Response::ok()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function error($message)
    {
        $doc = $this->renderer->render(['msg' => $message]);

        return Response::internalError()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function notFound($message)
    {
        $doc = $this->renderer->render(['msg' => $message]);

        return Response::notFound()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function badParams($data)
    {
        $doc = $this->renderer->render($data);

        return Response::badRequest()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function forbidden($message)
    {
        $doc = $this->renderer->render(['msg' => $message]);

        return Response::forbidden()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }
}
