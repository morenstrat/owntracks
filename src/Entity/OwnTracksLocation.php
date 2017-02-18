<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Defines the owntracks_location entity.
 *
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
 *       "add" = "Drupal\owntracks\Form\OwnTracksLocationForm",
 *       "edit" = "Drupal\owntracks\Form\OwnTracksLocationForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\owntracks\Access\OwnTracksLocationAccessControlHandler",
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
   * Definition of the allowed payload properties.
   *
   * @var array
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
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE)
      ->setDefaultValueCallback('Drupal\owntracks\Entity\OwnTracksLocation::getCurrentUserId');

    $fields['accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Accuracy'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm')
      ->setSetting('unsigned', TRUE);

    $fields['altitude'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Altitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm');

    $fields['battery_level'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Battery level'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', '%')
      ->setSetting('unsigned', TRUE)
      ->setSetting('size', 'tiny')
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => 0,
          'max' => 100,
        ],
      ]);

    $fields['heading'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Heading'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', '°')
      ->setSetting('unsigned', TRUE)
      ->setSetting('size', 'small')
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => 0,
          'max' => 360,
        ],
      ]);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['event'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Event'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('allowed_values', ['enter' => 'Enter', 'leave' => 'Leave']);

    $fields['latitude'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Latitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => ['scale' => 8],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', '°')
      ->setSetting('precision', 10)
      ->setSetting('scale', 8)
      ->setRequired(TRUE)
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => -90,
          'max' => 90,
        ],
      ]);

    $fields['longitude'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Longitude'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => ['scale' => 8],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', '°')
      ->setSetting('precision', 11)
      ->setSetting('scale', 8)
      ->setRequired(TRUE)
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => -180,
          'max' => 180,
        ],
      ]);

    $fields['radius'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Radius'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm')
      ->setSetting('unsigned', TRUE);

    $fields['trigger_id'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Trigger'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('allowed_values', [
        'c' => 'Circular',
        'b' => 'Beacon',
        'r' => 'Response',
        'u' => 'User',
        't' => 'Timer',
        'a' => 'Automatic',
      ]);

    $fields['tracker_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tracker-ID'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['timestamp'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Timestamp'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['vertical_accuracy'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vertical accuracy'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'm')
      ->setSetting('unsigned', TRUE);

    $fields['velocity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Velocity'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'km/h')
      ->setSetting('unsigned', TRUE);

    $fields['pressure'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Pressure'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('suffix', 'kPa')
      ->setSetting('unsigned', TRUE);

    $fields['connection'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Connection'))
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['label' => 'inline', 'weight' => 0])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('allowed_values', [
        'w' => 'WiFi',
        'o' => 'Offline',
        'm' => 'Mobile',
      ]);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function createFromRequest(Request $request) {
    $owntracks_location = OwnTracksLocation::create();
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
      if (isset($payload->{$property_name}) && !empty($payload->{$property_name})) {
        $owntracks_location->set($field_name, $payload->{$property_name});
      }
    }

    $violations = $owntracks_location->validate();

    if ($violations->count() !== 0) {
      throw new HttpException(400, 'Invalid location payload');
    }

    try {
      $owntracks_location->save();
    }
    catch (\Exception $e) {
      throw new HttpException(500, 'Internal server error');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation() {
    return [
      $this->get('latitude')->value,
      $this->get('longitude')->value,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->getEntityKey('uid');
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return array(\Drupal::currentUser()->id());
  }

}
