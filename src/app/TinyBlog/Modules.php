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

    protected $settings;

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function user()
    {
        return $this->lazy('user', [$this, 'makeModuleUser']);
    }

    public function article()
    {
        return $this->lazy('article', [$this, 'makeModuleArticle']);
    }

    public function comment()
    {
        return $this->lazy('comment', [$this, 'makeModuleComment']);
    }

    public function oauth()
    {
        return $this->lazy('oauth', [$this, 'makeModuleOAuth']);
    }

    public function tools()
    {
        return $this->lazy('tools', [$this, 'makeModuleTools']);
    }

    public function web()
    {
        return $this->lazy('web', [$this, 'makeModuleWeb']);
    }

    protected function getSqlDriver()
    {
        return $this->lazy('sql_driver', [$this, 'makeSqlDriver']);
    }

    protected function makeSqlDriver()
    {
        return new SqlDriver(
            $this->settings->get('db.dsn'),
            $this->settings->get('db.username'),
            $this->settings->get('db.password')
        );
    }

    protected function makeModuleUser()
    {
        return new ModuleUser($this->getSqlDriver());
    }

    protected function makeModuleArticle()
    {
        return new ModuleArticle($this->getSqlDriver());
    }

    protected function makeModuleComment()
    {
        return new ModuleComment($this->getSqlDriver());
    }

    protected function makeModuleOAuth()
    {
        return new ModuleOAuth($this->getSqlDriver(), $this->settings, $this->tools());
    }

    protected function makeModuleTools()
    {
        return new ModuleTools();
    }

    protected function makeModuleWeb()
    {
        return new ModuleWeb($this, $this->settings->get('web'));
    }
}
