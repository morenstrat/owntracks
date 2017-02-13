<?php

/**
 * @file
 * Preprocessors and theme functions of OwnTracks module.
 */
use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Html;

function template_preprocess_owntracks_map(&$variables) {
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
    'markers' => $variables['markers'],
    'processed' => FALSE,
  ];
}
