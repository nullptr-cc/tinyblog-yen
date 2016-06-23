<?php

namespace TinyBlog\Web\Responder;

use Yen\Http\Response;
use TinyBlog\Web\Presenter\Base\ComponentRegistry;

class HtmlResponder
{
    private $html_components;

    public function __construct(ComponentRegistry $html_components)
    {
        $this->html_components = $html_components;
    }

    public function ok($component, array $data = [])
    {
        $doc = $this->html_components->getComponent($component)->present($data);

        return Response::ok()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function error()
    {
        $doc = $this->html_components->getComponent('Error/Internal')->present();

        return Response::internalError()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function notFound()
    {
        $doc = $this->html_components->getComponent('Error/NotFound')->present();

        return Response::notFound()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }

    public function forbidden()
    {
        $doc = $this->html_components->getComponent('Error/Forbidden')->present();

        return Response::forbidden()
                ->withHeader('Content-Type', $doc->mime())
                ->withBody($doc->content());
    }
}
