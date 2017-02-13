<?php

/**
 * @file
 * Contains \Drupal\owntracks\Controller\OwnTracksLocationController.
 */
namespace Drupal\owntracks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\owntracks\Entity\OwnTracksLocation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OwntracksLocationController extends ControllerBase {

  /**
   * Receive and store OwnTracks locations.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   A post request object with content type application/json.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   An empty JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function post(Request $request) {
    OwnTracksLocation::createFromRequest($request);
    return new JsonResponse();
  }

}
