<?php

namespace Drupal\Tests\owntracks\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\owntracks\Entity\OwnTracksLocation;

/**
 * @coversDefaultClass \Drupal\owntracks\Entity\OwnTracksLocation
 * @group owntracks
 */
class OwnTracksLocationTest extends EntityKernelTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['owntracks', 'options'];

  /**
   * Sample owntracks location data.
   *
   * @var array
   */
  public static $sampleOwnTracksLocationData = [
    '_type' => 'location',
    'acc' => 75,
    'alt' => 13,
    'batt' => 100,
    'cog' => 270,
    'desc' => 'Office',
    'event' => 'enter',
    'lat' => 89.472499,
    'lon' => -179.4164046,
    'rad' => 270,
    't' => 'a',
    'tid' => '5X',
    'tst' => 1376715317,
    'vac' => 10,
    'vel' => 54,
    'p' => 50,
    'conn' => 'w',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('owntracks_location');
  }

  /**
   * Tests the owntracks location validation.
   */
  public function testValidation() {
    $content = [
      'accuracy'          => -1,        // invalid
      'altitude'          => 'invalid', // invalid
      'battery_level'     => 101,       // invalid
      'heading'           => 361,       // invalid
      'description'       => 'valid',   // valid
      'event'             => 'invalid', // invalid
      'latitude'          => -90.1,     // invalid
      'longitude'         => 180.1,     // invalid
      'radius'            => -1,        // invalid
      'trigger_id'        => 'invalid', // invalid
      'tracker_id'        => 'valid',   // valid
      'timestamp'         => 0,         // valid
      'vertical_accuracy' => -1,        // invalid
      'velocity'          => -1,        // invalid
      'pressure'          => -1,        // invalid
      'connection'        => 'invalid', // invalid
    ];

    $owntracks_location = OwnTracksLocation::create($content);
    $violations = $owntracks_location->validate();
    $this->assertEquals(13, $violations->count());
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Payload type not set
   */
  public function testPayloadTypeNotSetException() {
    $content = (object) self::$sampleOwnTracksLocationData;
    unset($content->_type);
    OwnTracksLocation::createFromObject($content);
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Invalid payload type
   */
  public function testInvalidPayloadTypeException() {
    $content = (object) self::$sampleOwnTracksLocationData;
    $content->_type = 'invalid';
    OwnTracksLocation::createFromObject($content);
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Invalid location payload
   */
  public function testInvalidLocationPayloadException() {
    $content = (object) self::$sampleOwnTracksLocationData;
    $content->batt = 101;
    OwnTracksLocation::createFromObject($content);
  }

}
