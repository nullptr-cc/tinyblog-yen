<?php

namespace TinyBlogWebTest;

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as ExpCond;

/**
 * @large
 */
class EntrancePageTest extends \TestExt\WebTestCase
{
    public function testFormView()
    {
        $wd = $this->getWebDriver();

        $wd->get($this->website_url->withPath('/auth/entrance'));
        $form = $wd->findElement(By::id('auth_form'));
        $inputs = $form->findElements(By::tagName('input'));
        $buttons = $form->findElements(By::tagName('button'));

        $this->assertTrue($form->isDisplayed());

        $this->assertCount(2, $inputs);
        foreach ($inputs as $input) {
            $this->assertTrue($input->isDisplayed());
            $this->assertTrue($input->isEnabled());
        };

        $this->assertCount(1, $buttons);
        $this->assertTrue($buttons[0]->isDisplayed());
        $this->assertTrue($buttons[0]->isEnabled());
    }

    public function testAuthSuccess()
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
            ExpCond::presenceOfElementLocated(By::id('sign_out_form'))
        );
        $this->assertEquals($this->website_url, $wd->getCurrentURL());
    }

    public function testAuthFail()
    {
        $wd = $this->getWebDriver();

        $wd->get($this->website_url->withPath('/auth/entrance'));
        $form = $wd->findElement(By::id('auth_form'));
        $inp_username = $form->findElement(By::name('username'));
        $inp_password = $form->findElement(By::name('password'));
        $btn_submit = $form->findElement(By::cssSelector('button[type="submit"]'));

        $inp_username->sendKeys('fakeuser');
        $inp_password->sendKeys('password');
        $btn_submit->click();

        $block_selector = '.uk-notify .uk-notify-message-danger div';

        $wd->wait(3, 500)->until(
            ExpCond::visibilityOfElementLocated(By::cssSelector($block_selector))
        );

        $msg = $wd->findElement(By::cssSelector($block_selector));
        $this->assertEquals('Invalid credentials', $msg->getText());

        $wd->wait(10, 500)->until(
            ExpCond::invisibilityOfElementLocated(By::cssSelector($block_selector))
        );
    }
}
