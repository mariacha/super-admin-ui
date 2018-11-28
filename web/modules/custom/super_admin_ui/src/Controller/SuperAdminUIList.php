<?php

namespace Drupal\super_admin_ui\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class SuperAdminUIList.
 */
class SuperAdminUIList extends ControllerBase {

  /**
   * List.
   *
   * @return string
   *   Return Hello string.
   */
  public function list() {
    foreach ($this->entityTypeManager()->getDefinitions() as $type) {
      $url = Url::fromUserInput('/admin/config/super-admin-ui/' . $type->id());
      $content[$type->getLabel()->render()][] = Link::fromTextAndUrl($type->getLabel(), $url);
    }

    ksort($content);

    $table = [
      '#type' => 'table',
      '#rows' => $content,
    ];

    return $table;
  }

}
