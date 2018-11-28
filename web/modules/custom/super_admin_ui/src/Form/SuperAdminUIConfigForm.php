<?php

namespace Drupal\super_admin_ui\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SuperAdminUIConfigForm.
 */
class SuperAdminUIConfigForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $config_options = [];

    foreach ($this->entityTypeManager->getDefinitions() as $type) {
      $config_options[$type->id()] = $type->getLabel();
    }

    asort($config_options);

    $super_admin_ui_config = $this->entity;
    $form['id'] = [
      '#type' => 'select',
      '#title' => $this->t('Config schema'),
      '#maxlength' => 255,
      '#default_value' => $super_admin_ui_config->id(),
      '#description' => $this->t("Label for the Super Admin UI config."),
      '#required' => TRUE,
      '#disabled' => !$super_admin_ui_config->isNew(),
      '#options' => $config_options,
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $super_admin_ui_config = $this->entity;
    $status = $super_admin_ui_config->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Super Admin UI config.', [
          '%label' => $super_admin_ui_config->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Super Admin UI config.', [
          '%label' => $super_admin_ui_config->label(),
        ]));
    }
    $form_state->setRedirectUrl($super_admin_ui_config->toUrl('collection'));
  }

}
