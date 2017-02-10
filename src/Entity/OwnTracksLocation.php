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

    $fields['acc'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Accuracy'));

    $fields['alt'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Altitude'));

    $fields['batt'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Battery level'));

    $fields['cog'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Course over ground'));

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'));

    $fields['event'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Event'));

    $fields['geolocation'] = BaseFieldDefinition::create('geolocation')
      ->setLabel(t('Geolocation'));

    $fields['rad'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Radius'));

    $fields['t'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Trigger'));

    $fields['tid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tracker-ID'));

    $fields['tst'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Timestamp'));

    $fields['vac'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vertical accuracy'));

    $fields['vel'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Velocity'));

    $fields['p'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Pressure'));

    $fields['conn'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Connection'));

    return $fields;
  }



}
