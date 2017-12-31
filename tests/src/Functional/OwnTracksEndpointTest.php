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
  protected function request($options) {
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
   */
  protected function getAuthorizationHeader(AccountInterface $account) {
    return 'Basic ' . base64_encode($account->name->value . ':' . $account->passRaw);
  }

}
