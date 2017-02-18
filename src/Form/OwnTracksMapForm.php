<?php

namespace Drupal\owntracks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\owntracks\OwnTracksLocationService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * OwnTracks date select form.
 */
class OwnTracksMapForm extends FormBase {

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
  public function buildForm(array $form, FormStateInterface $form_state, AccountInterface $account = NULL) {
    if ($account === NULL) {
      $account = $this->currentRouteMatch->getParameter('user');

      if (!$account instanceof AccountInterface) {
        $account = $this->currentUser();
      }
    }

    $options = [];

    for ($i = 1; $i <= 31; $i++) {
      $options[$i] = $i;
    }

    $form['day'] = [
      '#type' => 'select',
      '#title' => $this->t('Day'),
      '#options' => $options,
      '#default_value' => date('j', REQUEST_TIME),
      '#required' => TRUE,
      '#weight' => -10
    ];

    $options = [];

    for ($i = 1; $i <= 12; $i++) {
      $options[$i] = $i;
    }

    $form['month'] = [
      '#type' => 'select',
      '#title' => $this->t('Month'),
      '#options' => $options,
      '#default_value' => date('n', REQUEST_TIME),
      '#required' => TRUE,
      '#weight' => 0,
    ];

    $options = [];

    for ($i = 1978; $i <= date('Y', REQUEST_TIME); $i++) {
      $options[$i] = $i;
    }

    $form['year'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => $options,
      '#default_value' => date('Y', REQUEST_TIME),
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
      '#track' => $this->ownTracksLocationService->getUserTrack($account),
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
  }

}