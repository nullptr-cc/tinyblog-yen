<?php

namespace TinyBlog\Core\Contract;

interface IDependencyContainer
{
    public function getSettings();

    public function getRouter();
    public function getHandlerRegistry();

    public function getHtmlRenderer();
    public function getHtmlPresenter();
    public function getJsonPresenter();

    public function getDataAccessRegistry();
    public function getDomainRegistry();
    public function getValidators();
    public function getTools();

    public function getSession();
    public function getUserAuthenticator();
}
