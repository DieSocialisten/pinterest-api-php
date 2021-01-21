<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use \DirkGroenen\Pinterest\Pinterest;
use PHPUnit\Framework\TestCase;

class ImageUploadTest extends TestCase
{
  private Pinterest $pinterest;

  public function setUp(): void
  {
    // Setup Pinterest without the curlbuilder mock (since we wan't to test real interaction)
    $this->pinterest = new Pinterest(CLIENT_ID, CLIENT_SECRET);
    $this->pinterest->auth->setOAuthToken(ACCESS_TOKEN);
  }

  public function testCreatePinWithRealFileUpload()
  {
    /*
    $response = $this->pinterest->pins->create(array(
        "note"      => "Test pin from API wrapper. phpversion(" . phpversion() . ")",
        "image"     => __DIR__ . '/../testimage.jpg',
        "board"     => "503066289565421201"
    ));

    // Check if we got a pin back
    $this->assertInstanceOf( "DirkGroenen\Pinterest\Models\Pin", $response );

    // Delete pin
    $this->pinterest->pins->delete($response->id);
    */

    $this->markTestIncomplete(
      "This test has not been implemented yet."
    );
  }
}
