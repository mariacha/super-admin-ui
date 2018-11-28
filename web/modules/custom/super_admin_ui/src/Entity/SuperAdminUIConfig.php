<?php

namespace Drupal\super_admin_ui\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Super Admin UI config entity.
 *
 * @ConfigEntityType(
 *   id = "super_admin_ui_config",
 *   label = @Translation("Super Admin UI config"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\super_admin_ui\SuperAdminUIConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\super_admin_ui\Form\SuperAdminUIConfigForm",
 *       "edit" = "Drupal\super_admin_ui\Form\SuperAdminUIConfigForm",
 *       "delete" = "Drupal\super_admin_ui\Form\SuperAdminUIConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\super_admin_ui\SuperAdminUIConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "super_admin_ui_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/super-admin/super_admin_ui_config/{super_admin_ui_config}",
 *     "add-form" = "/admin/config/super-admin/super_admin_ui_config/add",
 *     "edit-form" = "/admin/config/super-admin/super_admin_ui_config/{super_admin_ui_config}/edit",
 *     "delete-form" = "/admin/config/super-admin/super_admin_ui_config/{super_admin_ui_config}/delete",
 *     "collection" = "/admin/config/super-admin/super_admin_ui_config"
 *   }
 * )
 */
class SuperAdminUIConfig extends ConfigEntityBase implements SuperAdminUIConfigInterface {

  /**
   * The Super Admin UI config ID.
   *
   * @var string
   */
  protected $id;

  /**
   * {@inheritdoc}
   *
   * Pull in the config entity's label.
   */
  public function label() {
    $id = $this->id();

    $original_config = $this->entityTypeManager()->getDefinition($id);
    return $original_config->getLabel();
  }
}
