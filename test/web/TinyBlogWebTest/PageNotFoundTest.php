<?php

namespace TinyBlogWebTest;

use Facebook\WebDriver\WebDriverBy as By;

class PageNotFoundTest extends \TestExt\WebTestCase
{
    public function test()
    {
        $wd = $this->getWebDriver();

        $wd->get($this->website_url->withPath('/not-existent-page'));
        $msg = $wd->findElement(By::tagname('h1'));

        $this->assertTrue($msg->isDisplayed());
        $this->assertEquals('404 - Page not found', $msg->getText());
    }
}
