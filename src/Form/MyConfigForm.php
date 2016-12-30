<?php

namespace Drupal\my_config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MyConfigForm.
 *
 * @package Drupal\my_config_form\Form
 */
class MyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'myform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('my_config_form.settings');
    $form['radio_button'] = [
      '#type' => 'radios',
      '#title' => $this->t('Radio Button'),
      '#options' => array(
        'off' => $this->t('Off'),
        'on' => $this->t('On'),
      ),
      '#default_value' => $config->get('radio'),
    ];
    $form['select_widget'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Widget'),
      '#options' => array(
        'one' => $this->t('one'),
        'two' => $this->t('two'),
        'three' => $this->t('three'),
        'four' => $this->t('four')
      ),
      '#size' => 1,
      '#default_value' => $config->get('select'),
    ];
    $form['some_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Some Text'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('text'),
      '#states' => [
        'visible' => [
          ':input[name="radio_button"]' => array('value' => 'on'),
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('my_config_form.settings')
      ->set('text', $form_state->getValue('some_text'))
      ->set('select', $form_state->getValue('select_widget'))
      ->set('radio', $form_state->getValue('radio_button'))
      ->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['my_config_form.settings'];
  }
}
