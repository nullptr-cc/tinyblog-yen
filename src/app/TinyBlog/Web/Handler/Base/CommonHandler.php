<?php

namespace TinyBlog\Web\Handler\Base;

abstract class CommonHandler extends Handler
{
    protected function ok($component, array $data = [])
    {
        $doc = $this->presenter->present($component, $data);
        return $this->responseOk($doc);
    }

    protected function notFound()
    {
        $doc = $this->presenter->present('Error/NotFound');
        return $this->responseNotFound($doc);
    }

    protected function forbidden()
    {
        $doc = $this->presenter->present('Error/Forbidden');
        return $this->responseForbidden($doc);
    }
}
