<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType(
 *   id = "owntracks_location",
 *   label = @Translation("OwnTracks Location"),
 *   label_singular = @Translation("owntracks location"),
 *   label_plural = @Translation("owntracks locations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count owntracks location",
 *     plural = "@count owntracks locations"
 *   ),
 *   base_table = "owntracks_location",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class OwnTracksLocation extends ContentEntityBase {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setSetting('target_type', 'user');

    $fields['accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Accuracy'));

    $fields['altitude'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Altitude'));

    $fields['battery_level'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Battery level'));

    $fields['heading'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Heading'));

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'));

    $fields['event'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Event'));

    $fields['geolocation'] = BaseFieldDefinition::create('geolocation')
      ->setLabel(t('Geolocation'));

    $fields['radius'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Radius'));

    $fields['trigger'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Trigger'));

    $fields['tracker_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tracker-ID'));

    $fields['timestamp'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Timestamp'));

    $fields['vertical_accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vertical accuracy'));

    $fields['velocity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Velocity'));

    $fields['pressure'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Pressure'));

    $fields['connection'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Connection'));

    return $fields;
  }



}
