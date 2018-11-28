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
    $config = \Drupal::entityTypeManager()->getStorage($entity->id());
    $properties = $config->getEntityType()->getPropertiesToExport();

    unset($properties['_core']);

    foreach ($properties as $property) {
      $header[$property] = $property;
    }

    // TODO: Right now this is dumb. Make the callback for this configurable.
    foreach ($config->loadMultiple() as $type) {
      foreach ($properties as $property) {
        $content[$type->id()][$property] = $type->get($property);
        if (is_array($content[$type->id()][$property])) {
          $content[$type->id()][$property] = 'array';
        }
      }
    }

    $table = [
      '#type' => 'table',
      '#rows' => $content,
      '#header' => $header,
    ];

    return $table;
  }

}
