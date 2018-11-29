<?php

namespace Drupal\super_admin_ui\Form;

use Drupal\Core\Config\Entity\ConfigEntityType;
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
    $super_admin_ui_config = $this->entity;
    $config_options = [];

    // Get all the config entity types.
    foreach ($this->entityTypeManager->getDefinitions() as $type) {
      if ($type instanceof ConfigEntityType) {
        $config_options[$type->id()] = $type->getLabel();
      }
    }

    // Remove any that we already have settings for.
    foreach ($this->entityTypeManager->getStorage('super_admin_ui_config')->loadMultiple() as $type) {
      if ($type->id() != $super_admin_ui_config->id()) {
        unset($config_options[$type->id()]);
      }
    }

    // Remove the references to this module's config.
    unset($config_options['super_admin_ui_config']);

    asort($config_options);

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

    if (!$super_admin_ui_config->isNew()) {
      $fields = $this->entity->getFields();

      if (!empty($fields)) {

        $form['fields'] = [
          '#type' => 'table',
          '#caption' => $this
            ->t('Fields available to this config'),
          '#header' => [
            'display_title' => $this->t('Name'),
            'id' => $this->t('Machine name'),
            'delete' => $this->t('Delete'),
          ],
        ];

        foreach ($fields as $key => $field) {
          $form['fields'][$key]['display_title'] = [
            '#type' => 'textfield',
            '#value' => $field['display_title'],
          ];
          $form['fields'][$key]['id'] = [
            '#markup' => $field['id'],
          ];
          $form['fields'][$key]['delete'] = [
            '#type' => 'checkbox',
          ];
        }
      }

      $available_fields = $this->entity->getAvailableFields();

      $form['add_field'] = [
        '#type' => 'select',
        '#title' => $this->t('Add a Field'),
        '#empty_value' => '',
        '#default_value' => 'none',
        '#description' => $this->t("Add a field to display on this config."),
        '#required' => FALSE,
        '#options' => $available_fields,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $super_admin_ui_config = $this->entity;

    $new_field = $form_state->getValue('add_field');

    if (!empty($new_field)) {
      $super_admin_ui_config->setField($new_field);
    }

    $fields = $form_state->getValue('field');

    foreach ($fields as $key => $field) {
      if ($field[$key]['delete']) {
        $super_admin_ui_config->unsetField($new_field);
      }
    }

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
