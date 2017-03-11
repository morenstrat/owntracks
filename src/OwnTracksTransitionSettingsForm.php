<?php

namespace Drupal\owntracks;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Configure owntracks_transition settings.
 */
class OwnTracksTransitionSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'owntracks_transition.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owntracks_transition_settings';
  }

}
