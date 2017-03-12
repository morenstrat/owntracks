<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the owntracks_transition entity.
 *
 * @ContentEntityType(
 *   id = "owntracks_transition",
 *   label = @Translation("OwnTracks Transition"),
 *   label_singular = @Translation("owntracks transition"),
 *   label_plural = @Translation("owntracks transitions"),
 *   label_count = @PluralTranslation(
 *     singular = "@count owntracks transition",
 *     plural = "@count owntracks transitions"
 *   ),
 *   base_table = "owntracks_transition",
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "form" = {
 *       "add" = "Drupal\owntracks\Form\OwnTracksEntityForm",
 *       "edit" = "Drupal\owntracks\Form\OwnTracksEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\owntracks\Access\OwnTracksEntityAccessControlHandler",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "canonical" = "/owntracks_transition/{owntracks_transition}",
 *     "add-form" = "/owntracks_transition/add",
 *     "edit-form" = "/owntracks_transition/{owntracks_transition}/edit",
 *     "delete-form" = "/owntracks_transition/{owntracks_transition}/delete",
 *   },
 *   admin_permission = "administer owntracks",
 *   field_ui_base_route = "entity.owntracks_transition.admin_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "label" = "description",
 *   },
 * )
 */
class OwnTracksTransition extends OwnTracksEntityBase implements OwnTracksEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['wtst'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Waypoint timestamp'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    /* @var \Drupal\Core\Field\BaseFieldDefinition $fields['t'] */
    $fields['t']->setSetting('allowed_values', [
      'c' => 'Circular',
      'b' => 'Beacon',
      'w' => 'WiFi',
    ]);

    return $fields;
  }

}
