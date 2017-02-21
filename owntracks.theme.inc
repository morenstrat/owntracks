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
  $variables['attributes'] = new Attribute([
    'id' => 'owntracks-map',
    'class' => ['owntracks-map'],
  ]);

  $variables['#attached']['library'][] = 'owntracks/owntracks';
  $variables['#attached']['drupalSettings']['owntracks']['track'] = $variables['track'];
}
