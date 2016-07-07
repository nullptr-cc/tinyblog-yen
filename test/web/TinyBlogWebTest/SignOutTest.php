<?php

namespace TinyBlogWebTest;

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as ExpCond;

/**
 * @large
 */
class SignOutTest extends \TestExt\WebTestCase
{
    public function testSignOutAfterSignIn()
    {
        $wd = $this->getWebDriver();

        $wd->get($this->website_url->withPath('/auth/entrance'));
        $form = $wd->findElement(By::id('auth_form'));
        $inp_username = $form->findElement(By::name('username'));
        $inp_password = $form->findElement(By::name('password'));
        $btn_submit = $form->findElement(By::cssSelector('button[type="submit"]'));

        $inp_username->sendKeys('foo');
        $inp_password->sendKeys('bar');
        $btn_submit->click();

        $wd->wait(10, 500)->until(
            ExpCond::presenceOfElementLocated(By::id('sign_out_link'))
        );

        $sign_out = $wd->findElement(By::id('sign_out_link'));
        $sign_out->click();

        $wd->wait(10, 500)->until(
            ExpCond::presenceOfElementLocated(By::cssSelector('a[href="auth/entrance"]'))
        );
    }
}
