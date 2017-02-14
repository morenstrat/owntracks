<?php

/**
 * @file
 * Preprocessors and theme functions of OwnTracks module.
 */

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Html;

/**
 * Prepares variables for owntracks_map templates.
 *
 * Default template: owntracks-map.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - track: An array of Lat/Lang coordinates.
 */
function template_preprocess_owntracks_map(array &$variables) {
  $class = 'owntracks-map';
  $id = Html::getUniqueId($class);

  $variables['attributes'] = new Attribute([
    'id' => $id,
    'class' => [$class],
  ]);

  $variables['#attached']['library'][] = 'owntracks/map';
  $variables['#attached']['drupalSettings']['owntracks']['maps'][] = [
    'id' => $id,
    'track' => $variables['track'],
    'processed' => FALSE,
  ];
}
