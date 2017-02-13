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
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "canonical" = "/owntracks_location/{owntracks_location}",
 *     "add-form" = "/owntracks_location/add",
 *     "edit-form" = "/owntracks_location/{owntracks_location}/edit",
 *     "delete-form" = "/owntracks_location/{owntracks_location}/delete",
 *   },
 *   admin_permission = "administer owntracks",
 *   field_ui_base_route = "entity.owntracks_location.admin_form",
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
    'latitude'          => 'lat',
    'longitude'         => 'lon',
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
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0]);

    $fields['accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Accuracy'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm');

    $fields['altitude'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Altitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm');

    $fields['battery_level'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Battery level'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', '%');

    $fields['heading'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Heading'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['event'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Event'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['latitude'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Latitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'settings' => ['scale' => 8], 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('precision', 10)
      ->setSetting('scale', 8);

    $fields['longitude'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Longitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'settings' => ['scale' => 8], 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('precision', 11)
      ->setSetting('scale', 8);

    $fields['radius'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Radius'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm');

    $fields['trigger_id'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Trigger'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['tracker_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tracker-ID'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['timestamp'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Timestamp'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

    $fields['vertical_accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vertical accuracy'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm');

    $fields['velocity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Velocity'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'km/h');

    $fields['pressure'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Pressure'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'kPa');

    $fields['connection'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Connection'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('view', TRUE);

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
      if (isset($payload->{$property_name})) {
        $owntracks_location->set($field_name, $payload->{$property_name});
      }
    }

    // @todo geofield/geolocation integration
    // geofield: $owntracks_location->set($field_name, 'POINT ('. $payload->lon .' '. $payload->lat .')');
    // geolocation: $owntracks_location->set($field_name, ['lat' => $payload->lat, 'lng' => $payload->lon]);

    return $owntracks_location;
  }

  public function getLocation() {
    return [
      $this->get('latitude')->value,
      $this->get('longitude')->value,
    ];
  }

}
