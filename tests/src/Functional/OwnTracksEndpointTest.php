<?php

namespace Drupal\Tests\owntracks\Functional;

use Drupal\Core\Session\AccountInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use GuzzleHttp\RequestOptions;

/**
 * Class OwnTracksEndpointTest.
 *
 * @group owntracks
 */
class OwnTracksEndpointTest extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['owntracks'];

  /**
   * The endpoint url.
   *
   * @var string
   */
  protected $endpointUrl;

  /**
   * Authorization header of unauthorized account.
   *
   * @var string
   */
  protected $unauthorizedHeader;

  /**
   * Authorization header of authorized account.
   *
   * @var string
   */
  protected $authorizedHeader;

  /**
   * Authorization header of admin account.
   *
   * @var string
   */
  protected $adminHeader;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->endpointUrl = Url::fromRoute('owntracks.endpoint')
      ->setAbsolute(TRUE)->toString();

    $unauthorizedAccount = $this->drupalCreateUser();
    $this->unauthorizedHeader = $this
      ->getAuthorizationHeader($unauthorizedAccount);

    $authorizedAccount = $this->drupalCreateUser(['create owntracks entities']);
    $this->authorizedHeader = $this
      ->getAuthorizationHeader($authorizedAccount);

    $adminAccount = $this->drupalCreateUser(['administer owntracks']);
    $this->adminHeader = $this
      ->getAuthorizationHeader($adminAccount);
  }

  /**
   * Test endpoint.
   */
  public function testEndpoint() {
    // Test anonymous request.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
      ],
    ]);
    $this->assertEquals(401, $response->getStatusCode());

    // Test unauthorized request.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->unauthorizedHeader,
      ],
    ]);
    $this->assertEquals(403, $response->getStatusCode());

    // Test request method.
    $this->drupalGet($this->endpointUrl);
    $this->assertSession()->statusCodeEquals(405);

    // Test request content type.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'text/html',
        'Authorization' => $this->authorizedHeader,
      ],
    ]);
    $this->assertEquals(415, $response->getStatusCode());

    // Test missing payload type.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->authorizedHeader,
      ],
      'body' => '{ "type": "missing" }',
    ]);
    $this->assertEquals(400, $response->getStatusCode());

    // Test invalid payload type.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->authorizedHeader,
      ],
      'body' => '{ "_type": "invalid" }',
    ]);
    $this->assertEquals(400, $response->getStatusCode());

    // Test incomplete payload.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->adminHeader,
      ],
      'body' => '{ "_type": "location" }',
    ]);
    $this->assertEquals(400, $response->getStatusCode());

    // Test location payload.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->authorizedHeader,
      ],
      'body' => '{"_type":"location","lat":"7","lon":"53","tst":"123456"}',
    ]);
    $this->assertEquals(200, $response->getStatusCode());

    // Test waypoints payload.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->authorizedHeader,
      ],
      'body' => '{"_type":"waypoints","waypoints":[{"_type":"waypoint","desc":"Office","lat":"53","lon":"7","rad":"100","tst":"123455"},{"_type":"waypoint","desc":"Home","lat":"54","lon":"6","rad":"100","tst":"123456"}]}',
    ]);
    $this->assertEquals(200, $response->getStatusCode());

    // Test transition payload.
    $response = $this->request([
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $this->authorizedHeader,
      ],
      'body' => '{"_type":"transition","lat":"7","lon":"53","tst":"123457","wtst":"123456","acc":"10"}',
    ]);
    $this->assertEquals(200, $response->getStatusCode());
  }

  /**
   * Send post request to the endpoint.
   *
   * @param array $options
   *   The request options.
   *
   * @return \GuzzleHttp\Psr7\Response
   *   The request response.
   */
  protected function request(array $options) {
    $options[RequestOptions::HTTP_ERRORS] = FALSE;
    $options[RequestOptions::ALLOW_REDIRECTS] = FALSE;

    return $this->getSession()->getDriver()->getClient()->getClient()
      ->request('POST', $this->endpointUrl, $options);
  }

  /**
   * Get authorization header for the given account.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   A user account.
   *
   * @return string
   *   The authorization header value.
   */
  protected function getAuthorizationHeader(AccountInterface $account) {
    return 'Basic ' . base64_encode($account->name->value . ':' . $account->passRaw);
  }

}
