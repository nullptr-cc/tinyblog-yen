<?php

namespace TinyBlog\Web\Handler\Base;

abstract class CommonHandler extends Handler
{
    protected function ok($component, array $data = [])
    {
        $doc = $this->getHtmlComponent($component)->present($data);
        return $this->responseOk($doc);
    }

    protected function error()
    {
        $doc = $this->getHtmlComponent('Error/Internal')->present();
        return $this->responseNotFound($doc);
    }

    protected function notFound()
    {
        $doc = $this->getHtmlComponent('Error/NotFound')->present();
        return $this->responseNotFound($doc);
    }

    protected function forbidden()
    {
        $doc = $this->getHtmlComponent('Error/Forbidden')->present();
        return $this->responseForbidden($doc);
    }

    protected function getHtmlComponent($name)
    {
        return $this->modules->web()->getHtmlComponents()->getComponent($name);
    }
}
