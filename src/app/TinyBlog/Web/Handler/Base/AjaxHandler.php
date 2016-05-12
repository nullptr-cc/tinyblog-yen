<?php

namespace TinyBlog\Web\Handler\Base;

use TinyBlog\Modules;

abstract class AjaxHandler extends Handler
{
    protected $json_renderer;

    public function __construct(Modules $modules)
    {
        parent::__construct($modules);
        $this->json_renderer = $modules->web()->getJsonRenderer();
    }

    protected function ok($data)
    {
        $doc = $this->json_renderer->render($data);
        return $this->responseOk($doc);
    }

    protected function error($message)
    {
        $doc = $this->json_renderer->render(['msg' => $message]);
        return $this->responseError($doc);
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
