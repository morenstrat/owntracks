<?php

namespace Drupal\owntracks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class OwnTracksEndpoint.
 */
class OwnTracksEndpoint extends ControllerBase {

  /**
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * OwnTracksEndpoint constructor.
   *
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   */
  public function __construct(SerializerInterface $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer')
    );
  }

  /**
   * Handle owntracks endpoint requests.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function handle(Request $request) {
    $content = $request->getContent();
    $this->serializer->deserialize($content, 'Drupal\owntracks\Entity\OwnTracksEntityInterface', 'json');
    return new Response('', 200, ['Content-Type' => 'application/json']);
  }

}