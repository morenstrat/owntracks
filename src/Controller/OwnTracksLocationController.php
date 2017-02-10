<?php

/**
 * @file
 * Contains \Drupal\owntracks\Controller\OwntracksLocationController.
 */
namespace Drupal\owntracks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\owntracks\Entity\OwnTracksLocation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OwntracksLocationController extends ControllerBase {

  /**
   * @var array $entityValues
   *   The owntrack_location entity values.
   */
  protected $entityValues;

  /**
   * @var array $payloadProperties
   *   Associative array of allowed payload property names mapped to their
   *   corresponding owntracks_location entity field names.
   */
  protected $payloadProperties = [
    'acc'   => 'accuracy',
    'alt'   => 'altitude',
    'batt'  => 'battery_level',
    'cog'   => 'heading',
    'desc'  => 'description',
    'event' => 'event',
    'lat'   => 'geolocation_lat',
    'lon'   => 'geolocation_lng',
    'rad'   => 'radius',
    't'     => 'trigger',
    'tid'   => 'tracker_id',
    'tst'   => 'timestamp',
    'vac'   => 'vertical_accuracy',
    'vel'   => 'velocity',
    'p'     => 'pressure',
    'conn'  => 'connection',
  ];

  /**
   * Receive and store an OwnTracks location.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   A post request object with content type application/json.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response object with status code indicating operation result.
   */
  public function post(Request $request) {
    $content = $request->getContent();
    $payload = json_decode($content);

    if (is_object($payload) && isset($payload->_type) && $payload->_type === 'location') {
      $this->prepareEntityValues($payload);

      try {
        $owntracks_location = OwnTracksLocation::create($this->entityValues);
        $owntracks_location->save();
        $status = 200;
      }
      catch (\Exception $e) {
        $status = 500;
      }
    }
    else {
      $status = 400;
    }

    return new JsonResponse(NULL, $status);
  }

  /**
   * @param \stdClass $payload
   *   A payload object sent by an OwnTracks client.
   */
  protected function prepareEntityValues($payload) {
    $this->entityValues = [
      'uid' => $this->currentUser()->id(),
    ];

    foreach ($this->payloadProperties as $property => $field) {
      if (isset($payload->{$property})) {
        $this->entityValues[$field] = $payload->{$property};
      }
    }
  }
}
