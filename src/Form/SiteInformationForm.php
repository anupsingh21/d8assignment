<?php

namespace Drupal\d8assignment\Form;

use Drupal\system\Form\SiteInformationForm as CoreSiteInformationForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Extend the core Configure site information settings for the site to add more fields.
 *
 * @internal
 */
class SiteInformationForm extends CoreSiteInformationForm {

  /**
   * Extend build form and additional fields.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $site_config = $this->config('system.site');
    $site_api_key = $site_config->get('siteapikey');
    
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_api_key,
      '#attributes' => ['placeholder' => t('No API Key yet')],
    ];

    if (!empty($site_api_key)) {
      $form['actions']['submit']['#value'] = t('Update configuration');
    }

    return $form;
  }

  /**
   * Extend submit form to handled the newly added fields.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $site_api_key = $form_state->getValue('siteapikey');

    $this->config('system.site')
          ->set('siteapikey', $site_api_key)
          ->save();
    if (!empty($site_api_key)) {
       $this->messenger()->addStatus($this->t('Site API Key is successfully saved as :key', [':key' => $site_api_key]));
    }
    parent::submitForm($form, $form_state);
  }
  
}
