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
    $data = (array) json_decode($data);

    if (!isset($data['_type'])) {
      throw new InvalidDataTypeException('Missing payload type');
    }

    if ($data['_type'] === 'location') {
      $entity = OwnTracksLocation::create($data);
    }
    elseif ($data['_type'] === 'transition') {
      $entity = OwnTracksTransition::create($data);
    }
    else {
      throw new InvalidDataTypeException('Invalid payload type');
    }

    if (!$entity instanceof OwnTracksEntityInterface) {
      throw new \Exception('Internal server error');
    }

    $violations = $entity->validate();

    if ($violations->count() !== 0) {
      throw new InvalidDataTypeException('Invalid payload data');
    }

    $entity->save();
  }

}
