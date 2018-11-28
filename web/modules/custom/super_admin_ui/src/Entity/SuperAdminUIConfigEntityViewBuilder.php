<?php

namespace Drupal\super_admin_ui\Entity;

use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Base class for entity view builders.
 *
 * @ingroup entity_api
 */
class SuperAdminUIConfigEntityViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $entity, $view_mode = 'full', $langcode = NULL) {
    foreach (\Drupal::entityTypeManager()->getStorage($entity->id())->loadMultiple() as $type) {
      $content[$type->id()][] = $type->label();
    }

    $table = [
      '#type' => 'table',
      '#rows' => $content,
    ];

    return $table;
  }

}
