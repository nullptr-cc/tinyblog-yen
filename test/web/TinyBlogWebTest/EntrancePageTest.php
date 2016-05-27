<?php

namespace TinyBlogWebTest;

use Facebook\WebDriver\WebDriverBy as By;

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
}
