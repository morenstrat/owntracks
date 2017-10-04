<?php

namespace Drupal\owntracks;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Configure owntracks_waypoint settings.
 */
class OwnTracksWaypointSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'owntracks_waypoint.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owntracks_waypoint_settings';
  }

}
