<?php

namespace Drupal\owntracks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\owntracks\Entity\OwnTracksLocationInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'UserTracks' Block.
 *
 * @Block(
 *   id = "user_tracks_block",
 *   admin_label = @Translation("User tracks block"),
 * )
 */
class UserTracksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Routing\CurrentRouteMatch definition.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $current_route_match;

  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entity_query;


  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entity_type_manager;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param CurrentRouteMatch $current_route_match
   *   The current route match service.
   * @param QueryFactory $entity_query
   *   The entity query service.
   * @param EntityTypeManagerInterface $entity_type_manager.
   *   The entity type manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    CurrentRouteMatch $current_route_match,
    QueryFactory $entity_query,
    EntityTypeManagerInterface $entity_type_manager

  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_route_match = $current_route_match;
    $this->entity_query = $entity_query;
    $this->entity_type_manager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity.query'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->current_route_match->getParameter('user');
    $track = [];

    if ($user instanceof UserInterface) {
      $query = $this->entity_query
        ->get('owntracks_location')
        ->condition('uid', $user->id());

      $result = $query->execute();

      if (!empty($result)) {
        $entities = $this->entity_type_manager
          ->getStorage('owntracks_location')
          ->loadMultiple($result);

        /** @var OwnTracksLocationInterface $owntracks_location */
        foreach ($entities as $owntracks_location) {
          $track[] = $owntracks_location->getLocation();
        }
      }
    }

    return array(
      '#theme' => 'owntracks_map',
      '#track' => $track,
      '#cache' => [
        'context' => ['url.path'],
      ],
    );
  }

}