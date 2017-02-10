<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class OwnTracksLocation extends ContentEntityBase implements OwnTracksLocationInterface {

  /**
   * @var array $payloadProperties
   *   Associative array of allowed payload property names mapped to their
   *   corresponding  field names.
   */
  protected $payloadProperties = [
    'accuracy'          => 'acc',
    'altitude'          => 'alt',
    'battery_level'     => 'batt',
    'heading'           => 'cog',
    'description'       => 'desc',
    'event'             => 'event',
    'geolocation'       => [
      'lat' => 'lat',
      'lon' => 'lng',
    ],
    'radius'            => 'rad',
    'trigger_id'        => 't',
    'tracker_id'        => 'tid',
    'timestamp'         => 'tst',
    'vertical_accuracy' => 'vac',
    'velocity'          => 'vel',
    'pressure'          => 'p',
    'connection'        => 'conn',
  ];

  /**
   * @inheritdoc
   */
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

    $fields['trigger_id'] = BaseFieldDefinition::create('list_string')
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

  /**
   * @param Request $request
   * @param AccountInterface $account
   * @return OwnTracksLocationInterface
   */
  public static function createFromRequest(Request $request, AccountInterface $account) {
    $owntracks_location = OwnTracksLocation::create(['uid' => $account->id()]);
    $content = $request->getContent();
    $payload = json_decode($content);

    if (!is_object($payload)) {
      throw new HttpException(400, 'Invalid request content');
    }

    if (!isset($payload->_type)) {
      throw new HttpException(400, 'Payload type not set');
    }

    if ($payload->_type !== 'location') {
      throw new HttpException(400, 'Invalid payload type');
    }

    foreach ($owntracks_location->payloadProperties as $field_name => $property_name) {
      if (is_array($property_name)) {
        $value = [];

        foreach ($property_name as $property_key => $field_key) {
          if (isset($payload->{$property_key})) {
            $value[$field_key] = $payload->{$property_key};
          }
        }

        $owntracks_location->set($field_name, $value);
      }
      else {
        if (isset($payload->{$property_name})) {
          $owntracks_location->set($field_name, $payload->{$property_name});
        }
      }
    }

    return $owntracks_location;
  }

}
