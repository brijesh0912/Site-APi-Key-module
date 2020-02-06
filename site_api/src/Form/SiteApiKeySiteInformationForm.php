<?php

namespace Drupal\site_api\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

// This is the form we are extending.
use Drupal\system\Form\SiteInformationForm;

class SiteApiKeySiteInformationForm extends SiteInformationForm {

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form = parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => t("An API Key to access site pages in JSON format."),
    ];

    // Change form submit button text to 'Update Configuration'
    if ($site_config->get('siteapikey')) {
      $form['actions']['submit']['#value'] = t('Update configuration');
    }
    return $form;
	}

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('siteapikey'))
      ->save();

    // Add message that Site API Key has been set.
    drupal_set_message("Successfully set Site API Key value to " . $form_state->getValue('siteapikey'));

    parent::submitForm($form, $form_state);
  }
}
