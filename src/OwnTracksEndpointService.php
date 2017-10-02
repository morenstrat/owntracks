<?php

namespace Drupal\owntracks;

use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\owntracks\Entity\OwnTracksEntityInterface;
use Drupal\owntracks\Entity\OwnTracksLocation;
use Drupal\owntracks\Entity\OwnTracksTransition;

/**
 * Provides the owntracks endpoint service.
 */
class OwnTracksEndpointService {

  /**
   * Create an OwnTracks entity.
   *
   * @param $data
   *
   * @throws \Drupal\Component\Serialization\Exception\InvalidDataTypeException
   * @throws \Exception
   */
  public function create($data) {
    $json = (array) json_decode($data);

    if (!isset($json['_type'])) {
      throw new InvalidDataTypeException('Missing payload type: ' . $data);
    }

    if ($json['_type'] === 'location') {
      $entity = OwnTracksLocation::create($json);
    }
    elseif ($json['_type'] === 'transition') {
      $entity = OwnTracksTransition::create($json);
    }
    else {
      throw new InvalidDataTypeException('Invalid payload type:' . $data);
    }

    if (!$entity instanceof OwnTracksEntityInterface) {
      throw new \Exception('Internal server error:' . $data);
    }

    $violations = $entity->validate();

    if ($violations->count() !== 0) {
      throw new InvalidDataTypeException('Invalid payload data: ' . $data);
    }

    $entity->save();
  }

}
