<?php

namespace Drupal\Tests\owntracks\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use GuzzleHttp\RequestOptions;

/**
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
  protected $url;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->url = Url::fromRoute('owntracks.endpoint')
      ->setAbsolute(TRUE)->toString();
  }

  /**
   * Tests the owntracks endpoint.
   */
  public function testEndpoint() {
    // Test anonymous request.
    $response = $this->request('POST', [
      'headers' => ['Content-Type' => 'application/json'],
    ]);
    $this->assertEquals(401, $response->getStatusCode());

    // Test authenticated request without permission.
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);
    $response = $this->request('POST', [
      'headers' => ['Content-Type' => 'application/json'],
    ]);
    $this->assertEquals(401, $response->getStatusCode());

    // Test request method.
    $account = $this->drupalCreateUser(['administer owntracks']);
    $this->drupalLogin($account);
    $response = $this->request('GET');
    $this->assertEquals(405, $response->getStatusCode());

    // Test content type.
    $account = $this->drupalCreateUser(['create owntracks entities']);
    $this->drupalLogin($account);
    $response = $this->request('POST', [
      'headers' => ['Content-Type' => 'text/html'],
    ]);
    $this->assertEquals(415, $response->getStatusCode());
  }

  /**
   * Send a request to the endpoint.
   *
   * @param string $method
   *   The request HTTP method.
   * @param array $options
   *   The request options.
   *
   * @return \Psr\Http\Message\ResponseInterface
  */
  protected function request($method, array $options = []) {
    $options[RequestOptions::HTTP_ERRORS] = FALSE;
    $options[RequestOptions::ALLOW_REDIRECTS] = FALSE;

    return $this->getSession()->getDriver()->getClient()->getClient()
      ->request($method, $this->url, $options);
  }

}
