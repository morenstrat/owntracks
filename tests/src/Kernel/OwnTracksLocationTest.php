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
  public static $sampleJsonData = [
    '_type' => 'location',
    'acc'   => 75,
    'alt'   => 13,
    'batt'  => 100,
    'cog'   => 270,
    'desc'  => 'Office',
    'event' => 'enter',
    'lat'   => 89.472499,
    'lon'   => -179.4164046,
    'rad'   => 270,
    't'     => 'a',
    'tid'   => '5X',
    'tst'   => 1376715317,
    'vac'   => 10,
    'vel'   => 54,
    'p'     => 50,
    'conn'  => 'w',
  ];

  public static $sampleValidEntityData = [
    'accuracy'          => 0,
    'altitude'          => 0,
    'battery_level'     => 100,
    'heading'           => 270,
    'description'       => 'valid',
    'event'             => 'enter',
    'latitude'          => -90,
    'longitude'         => 180,
    'radius'            => 100,
    'trigger_id'        => 'a',
    'tracker_id'        => 'fc',
    'timestamp'         => 10101603,
    'vertical_accuracy' => 0,
    'velocity'          => 0,
    'pressure'          => 0,
    'connection'        => 'w',
  ];

  public static $sampleInvalidEntityData = [
    'accuracy'          => -1,        // invalid 1
    'altitude'          => 'invalid', // invalid 2
    'battery_level'     => 101,       // invalid 3
    'heading'           => 361,       // invalid 4
    'description'       => 'valid',   // valid
    'event'             => 'invalid', // invalid 5
    'latitude'          => -90.1,     // invalid 6
    'longitude'         => 180.1,     // invalid 7
    'radius'            => -1,        // invalid 8
    'trigger_id'        => 'invalid', // invalid 9
    'tracker_id'        => 'valid',   // valid
    'timestamp'         => 0,         // valid
    'vertical_accuracy' => -1,        // invalid 10
    'velocity'          => -1,        // invalid 11
    'pressure'          => -1,        // invalid 12
    'connection'        => 'invalid', // invalid 13
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
    $owntracks_location = OwnTracksLocation::create(self::$sampleInvalidEntityData);
    $violations = $owntracks_location->validate();
    $this->assertEquals(13, $violations->count());
  }

  /**
   * Tests the owntracks location storage.
   */
  public function testStorage() {
    $owntracks_location = OwnTracksLocation::create(self::$sampleValidEntityData);
    $violations = $owntracks_location->validate();
    $this->assertEquals(0, $violations->count());
    $owntracks_location->save();
    $this->assertEquals([-90, 180], $owntracks_location->getLocation());
  }

  /**
   * @expectedException \Drupal\Core\Entity\EntityStorageException
   */
  public function testEntityStorageException() {
    OwnTracksLocation::create(self::$sampleInvalidEntityData)->save();
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Payload type not set
   */
  public function testPayloadTypeNotSetException() {
    $content = (object) self::$sampleJsonData;
    unset($content->_type);
    OwnTracksLocation::createFromObject($content);
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Invalid payload type
   */
  public function testInvalidPayloadTypeException() {
    $content = (object) self::$sampleJsonData;
    $content->_type = 'invalid';
    OwnTracksLocation::createFromObject($content);
  }

  /**
   * @covers ::createFromObject
   * @expectedException \Exception
   * @expectedExceptionMessage Invalid location payload
   */
  public function testInvalidLocationPayloadException() {
    $content = (object) self::$sampleJsonData;
    $content->batt = 101;
    OwnTracksLocation::createFromObject($content);
  }

}
