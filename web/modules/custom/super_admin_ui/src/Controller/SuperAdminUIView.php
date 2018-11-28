<?php

namespace Drupal\super_admin_ui\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class SuperAdminUIView.
 */
class SuperAdminUIView extends ControllerBase {

  /**
   * @param $config_entity_type
   *   A ConfigEntityType -- you can get a list searching for `@ConfigEntityType`.
   *
   * @return array
   */
  public function view($config_entity_type) {
    // TODO: $config_entity_type is a string. Is there a way to get it as an entity already?

    foreach ($this->entityTypeManager()->getStorage($config_entity_type)->loadMultiple() as $type) {
      $access = $this->entityTypeManager()->getAccessControlHandler('node')->createAccess($type->id(), NULL, [], TRUE);
      if ($access->isAllowed()) {
        $content[$type->id()][] = $type->label();
      }
    }

    $table = [
      '#type' => 'table',
      '#rows' => $content,
    ];

    return $table;
  }

}
