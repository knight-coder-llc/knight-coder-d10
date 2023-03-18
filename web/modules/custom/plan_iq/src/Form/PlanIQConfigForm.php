<?php
namespace Drupal\plan_iq\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Function to create the ChatGPT Config Form.
 */
class PlanIQConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'plan_iq.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'plan_iq_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('plan_iq.adminsettings');
    
    $form['plan_iq_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Endpoint'),
      '#description' => $this->t('Please provide the ChatGPT Completion API Endpoint here.'),
      '#default_value' => $config->get('plan_iq_endpoint') ? $config->get('plan_iq_endpoint') : 'https://api.openai.com/v1/completions',
      '#required' => TRUE,
    ];

    $form['plan_iq_model'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Model'),
      '#description' => $this->t('Please provide the ChatGPT API Model here. List of models can be found
                        <a href="https://beta.openai.com/docs/models/gpt-3" target="_blank"><b>Here</b></a>'),
      '#default_value' => $config->get('plan_iq_model'),
      '#required' => TRUE,
    ];

    $form['plan_iq_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT Access Token'),
      '#description' => $this->t('Please provide the ChatGPT Access Token here.'),
      '#default_value' => $config->get('plan_iq_token'),
      '#required' => TRUE,
    ];

    $form['plan_iq_max_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Max Token'),
      '#description' => $this->t('Please provide the ChatGPT max token here to limit the output words. Max token is the 
                        limit <br>of tokens combining both input prompt and output text. 1 token is approx 4 chars in English.
                        <br>You can use this <a href="https://platform.openai.com/tokenizer" target="_blank"><b>Tokenizer Tool</b></a> 
                        to count number of tokens for your text.'),
      '#default_value' => $config->get('plan_iq_max_token'),
      '#required' => TRUE,
    ];

    $form['plan_iq_temperature'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Temperature'),
      '#description' => $this->t('Please provide the ChatGPT temperature here. Temperature is a value between 0 and 1 
                        that controls the randomness of the output. Lower temperature results in less random completions. 
                        As the temperature approaches zero, the model will become deterministic and repetitive. 
                        Higher temperature results in more random completions. As the temperature approaches one, the model 
                        will become very creative and unpredictable.'),
      '#default_value' => $config->get('plan_iq_temperature'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('plan_iq.adminsettings')
      ->set('plan_iq_endpoint', $form_state->getValue('plan_iq_endpoint'))
      ->set('plan_iq_model', $form_state->getValue('plan_iq_model'))
      ->set('plan_iq_token', $form_state->getValue('plan_iq_token'))
      ->set('plan_iq_temperature', $form_state->getValue('plan_iq_temperature'))
      ->set('plan_iq_max_token', $form_state->getValue('plan_iq_max_token'))
      ->save();

  }

}
