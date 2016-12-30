<?php

namespace Drupal\Tests\my_config_form\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\JavascriptTestBase;

/**
 * Class MyFormJavascriptTest
 * @group TrainingCards
 */
class MyFormJavascriptTest extends JavascriptTestBase {
  public static $modules = ['my_config_form'];

  public function testTextboxVisibility() {
    $this->drupalLogin($this->drupalCreateUser());
    $this->drupalGet('my_config_form/form/my_config');

    $page = $this->getSession()->getPage();
    // Verify textfield starts invisible
    $textfield = $page->findField('some_text');
    $this->assertFalse($textfield->isVisible());

    // Verify we can make it visible
    $radiobutton = $page->findField('radio_button');
    $radiobutton->setValue('on');
    $this->assertTrue($textfield->isVisible());
    // Save the form
    $this->submitForm([], 'Save configuration');
    $this->assertSession()->pageTextContains('The configuration options have been saved.');

    // Verify that the textfield now starts visible
    $this->assertTrue($textfield->isVisible());

    // Verify backwards
    $radiobutton->setValue('off');
    $this->assertFalse($textfield->isVisible());
    $this->submitForm([], 'Save configuration');
    $this->assertSession()->pageTextContains('The configuration options have been saved.');
    $this->assertFalse($textfield->isVisible());
  }
}
