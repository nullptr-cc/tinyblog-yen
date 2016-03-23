<?php

namespace TinyBlog\Web\Handler\Base;

use TinyBlog\Core\Contract\IDependencyContainer;

abstract class AjaxHandler extends Handler
{
    protected $json_renderer;

    public function __construct(IDependencyContainer $dc)
    {
        parent::__construct($dc);
        $this->json_renderer = $dc->getJsonRenderer();
    }

    protected function ok($data)
    {
        $doc = $this->json_renderer->render($data);
        return $this->responseOk($doc);
    }

    protected function notFound($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);
        return $this->responseNotFound($doc);
    }

    protected function badParams($data)
    {
        $doc = $this->json_renderer->render($data);
        return $this->responseBadRequest($doc);
    }

    protected function forbidden($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);
        return $this->responseNotFound($doc);
    }
}
