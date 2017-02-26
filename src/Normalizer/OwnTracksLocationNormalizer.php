<?php

namespace Drupal\owntracks\Normalizer;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\serialization\Normalizer\ComplexDataNormalizer;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Normalizes/denormalizes OwnTracks Location entity objects into an array
 * structure.
 */
class OwnTracksLocationNormalizer extends ComplexDataNormalizer implements DenormalizerInterface {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\owntracks\Entity\OwnTracksLocationInterface'];

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs an OwnTracksLocationNormalizer object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = array()) {
    // Validate the _type property.
    if (!isset($data['_type']) || $data['_type'] != 'location') {
      throw new UnexpectedValueException('Invalid or missing payload type');
    }

    // Remove the _type property.
    unset($data['_type']);

    // Rename desc property because desc is a reserved sql keyword.
    $data['description'] = $data['desc'];
    unset($data['desc']);

    // Create the entity from data.
    $entity = $this->entityTypeManager->getStorage('owntracks_location')->create($data);

    // Pass the names of the fields whose values can be merged.
    // @todo https://www.drupal.org/node/2456257 remove this.
    $entity->_restSubmittedFields = array_keys($data);

    return $entity;
  }

}
