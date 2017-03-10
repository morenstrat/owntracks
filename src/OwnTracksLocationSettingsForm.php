<?php

namespace Drupal\owntracks;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Configure owntracks_location settings.
 */
class OwnTracksLocationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'owntracks_location.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owntracks_location_settings';
  }

}
