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
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    'lat'   => [
      'geolocation' => 'lat',
     ],
    'lon'   => [
      'geolocation' => 'lng',
     ],
    'rad'   => 'radius',
    't'     => 'trigger_id',
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
   *   An empty JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function post(Request $request) {
    $content = $request->getContent();
    $payload = json_decode($content);

    if (!is_object($payload)) {
      throw new HttpException(400, 'Invalid request body');
    }

    if (!isset($payload->_type)) {
      throw new HttpException(400, 'Payload type not set');
    }

    if ($payload->_type === 'location') {
      try {
        $this->prepareEntityValues($payload);
        $owntracks_location = OwnTracksLocation::create($this->entityValues);
        $owntracks_location->save();
      }
      catch (\Exception $e) {
        throw new HttpException(500, $e->getMessage());
      }
    } else {
      throw new HttpException(400, 'Invalid payload type');
    }

    return new JsonResponse();
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
        if (is_array($field)) {
          $this->entityValues[key($field)][current($field)] = $payload->{$property};
        }
        else {
          $this->entityValues[$field] = $payload->{$property};
        }
      }
    }
  }
}
