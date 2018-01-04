<?php

namespace Drupal\Tests\owntracks\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;

/**
 * Class OwnTracksAccessTest.
 *
 * @covers \Drupal\owntracks\Access\OwnTracksEntityAccessControlHandler
 * @covers \Drupal\owntracks\Access\OwnTracksUserMapAccess
 * @covers \Drupal\owntracks\Plugin\views\access\OwnTracks
 *
 * @group owntracks
 */
class OwnTracksAccessTest extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['owntracks'];

  /**
   * Tests owntracks access controllers and plugins.
   */
  public function testAccess() {
    // Prepare accounts and urls for testing user map and views.
    $account = $this->createUser();
    $accountMapUrl = Url::fromRoute('owntracks.user_map', ['user' => $account->id()])
      ->setAbsolute(TRUE)->toString();
    $accountViewsUrl = Url::fromRoute('view.owntracks_location.current', ['user' => $account->id()])
      ->setAbsolute(TRUE)->toString();
    $viewOwnAccount = $this->createUser(['view own owntracks entities']);
    $viewOwnAccountMapUrl = Url::fromRoute('owntracks.user_map', ['user' => $viewOwnAccount->id()])
      ->setAbsolute(TRUE)->toString();
    $viewOwnAccountViewsUrl = Url::fromRoute('view.owntracks_location.current', ['user' => $viewOwnAccount->id()])
      ->setAbsolute(TRUE)->toString();
    $viewAnyAccount = $this->createUser(['view any owntracks entity']);
    $viewAnyAccountMapUrl = Url::fromRoute('owntracks.user_map', ['user' => $viewAnyAccount->id()])
      ->setAbsolute(TRUE)->toString();
    $viewAnyAccountViewsUrl = Url::fromRoute('view.owntracks_location.current', ['user' => $viewAnyAccount->id()])
      ->setAbsolute(TRUE)->toString();
    $adminAccount = $this->createUser(['administer owntracks']);
    $adminAccountMapUrl = Url::fromRoute('owntracks.user_map', ['user' => $adminAccount->id()])
      ->setAbsolute(TRUE)->toString();
    $adminAccountViewsUrl = Url::fromRoute('view.owntracks_location.current', ['user' => $adminAccount->id()])
      ->setAbsolute(TRUE)->toString();

    // Test user without permission.
    $this->drupalLogin($account);
    $this->drupalGet($accountMapUrl);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($viewOwnAccountMapUrl);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($accountViewsUrl);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($viewOwnAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(403);

    // Test user with permission to view own owntracks entities.
    $this->drupalLogin($viewOwnAccount);
    $this->drupalGet($viewOwnAccountMapUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($viewAnyAccountMapUrl);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($viewOwnAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($viewAnyAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(403);

    // Test user with permission to view any owntracks entity.
    $this->drupalLogin($viewAnyAccount);
    $this->drupalGet($viewAnyAccountMapUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($adminAccountMapUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($viewAnyAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($adminAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(200);

    // Test user with administer owntracks permission.
    $this->drupalLogin($adminAccount);
    $this->drupalGet($viewAnyAccountMapUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($adminAccountMapUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($viewAnyAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($adminAccountViewsUrl);
    $this->assertSession()->statusCodeEquals(200);

    // @todo: test entity access
  }

}
