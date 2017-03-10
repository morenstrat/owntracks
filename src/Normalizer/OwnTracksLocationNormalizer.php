<?php

namespace Drupal\owntracks\Normalizer;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Denormalizes OwnTracks Location data into an entity object.
 */
class OwnTracksLocationNormalizer extends ContentEntityNormalizer implements DenormalizerInterface {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\owntracks\Entity\OwnTracksLocationInterface'];

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = array()) {
    // Remove the payload _type property.
    if (isset($data['_type'])) {
      unset($data['_type']);
    }

    // Rename desc property because desc is a reserved sql keyword.
    if (isset($data['desc'])) {
      $data['description'] = $data['desc'];
      unset($data['desc']);
    }

    // Create the entity from data.
    $entity = $this->entityManager->getStorage('owntracks_location')->create($data);

    // Pass the names of the fields whose values can be merged.
    // @todo https://www.drupal.org/node/2456257 remove this.
    $entity->_restSubmittedFields = array_keys($data);

    return $entity;
  }

}
