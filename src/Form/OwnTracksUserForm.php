<?php

namespace Drupal\owntracks\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\owntracks\OwnTracksLocationService;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * OwnTracks user form.
 */
class OwnTracksUserForm extends FormBase {

  /**
   * CurrentRouteMatch definition.
   *
   * @var CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * OwntracksLocationService definition.
   *
   * @var OwnTracksLocationService
   */
  protected $ownTracksLocationService;

  /**
   * OwnTracksDateSelectForm constructor.
   */
  public function __construct(CurrentRouteMatch $current_route_match, OwnTracksLocationService $owntracks_location_service) {
    $this->currentRouteMatch = $current_route_match;
    $this->ownTracksLocationService = $owntracks_location_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('owntracks.location_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owntracks_date_select_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, UserInterface $user = NULL) {
    if ($user === NULL) {
      $user = $this->currentUser();
    }

    $date = DrupalDateTime::createFromArray([
      'day'   => $form_state->getValue('day', date('j', REQUEST_TIME)),
      'month' => $form_state->getValue('month', date('n', REQUEST_TIME)),
      'year'  => $form_state->getValue('year', date('Y', REQUEST_TIME)),
    ]);

    $options = [];

    for ($i = 1; $i <= 31; $i++) {
      $options[$i] = $i;
    }

    $form['day'] = [
      '#type' => 'select',
      '#title' => $this->t('Day'),
      '#options' => $options,
      '#default_value' => $date->format('j'),
      '#required' => TRUE,
      '#weight' => -10,
    ];

    $options = [];

    for ($i = 1; $i <= 12; $i++) {
      $options[$i] = $i;
    }

    $form['month'] = [
      '#type' => 'select',
      '#title' => $this->t('Month'),
      '#options' => $options,
      '#default_value' => $date->format('n'),
      '#required' => TRUE,
      '#weight' => 0,
    ];

    $options = [];

    for ($i = 1978; $i <= $date->format('Y'); $i++) {
      $options[$i] = $i;
    }

    $form['year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => $options,
      '#default_value' => $date->format('Y'),
      '#required' => TRUE,
      '#weight' => 10,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#weight' => 20,
    ];

    $form['map'] = [
      '#theme' => 'owntracks_map',
      '#track' => $this->ownTracksLocationService->getUserTrack($user, $date),
      '#weight' => 30,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!checkdate($form_state->getValue('month'), $form_state->getValue('day'), $form_state->getValue('year'))) {
      $form_state->setErrorByName('day', $this->t('Please select a valid date.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
  }

}
