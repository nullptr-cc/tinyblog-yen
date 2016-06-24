<?php

namespace TinyBlog;

use Yada\Driver as SqlDriver;
use Yen\Util\LazyContainer;
use Yen\Settings\Contract\ISettings;
use TinyBlog\User\ModuleUser;
use TinyBlog\Article\ModuleArticle;
use TinyBlog\Comment\ModuleComment;
use TinyBlog\OAuth\ModuleOAuth;
use TinyBlog\Tools\ModuleTools;
use TinyBlog\Web\ModuleWeb;

class Modules
{
    use LazyContainer;

    private $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function user()
    {
        return $this->lazy('user', [$this, 'makeModuleUser']);
    }

    private function makeModuleUser()
    {
        return new ModuleUser($this->getSqlDriver());
    }

    public function article()
    {
        return $this->lazy('article', [$this, 'makeModuleArticle']);
    }

    private function makeModuleArticle()
    {
        return new ModuleArticle($this->getSqlDriver());
    }

    public function comment()
    {
        return $this->lazy('comment', [$this, 'makeModuleComment']);
    }

    private function makeModuleComment()
    {
        return new ModuleComment($this->getSqlDriver());
    }

    public function oauth()
    {
        return $this->lazy('oauth', [$this, 'makeModuleOAuth']);
    }

    private function makeModuleOAuth()
    {
        return new ModuleOAuth($this->getSqlDriver(), $this->settings, $this->tools());
    }

    public function tools()
    {
        return $this->lazy('tools', [$this, 'makeModuleTools']);
    }

    private function makeModuleTools()
    {
        return new ModuleTools();
    }

    public function web()
    {
        return $this->lazy('web', [$this, 'makeModuleWeb']);
    }

    private function makeModuleWeb()
    {
        return new ModuleWeb($this, $this->settings->get('web'));
    }

    private function getSqlDriver()
    {
        return $this->lazy('sql_driver', [$this, 'makeSqlDriver']);
    }

    private function makeSqlDriver()
    {
        return new SqlDriver(
            $this->settings->get('db.dsn'),
            $this->settings->get('db.username'),
            $this->settings->get('db.password')
        );
    }
}
