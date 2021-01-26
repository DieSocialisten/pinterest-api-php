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

use DirkGroenen\Pinterest\Tests\PinterestAuthAwareTestCase;

class SectionsTest extends PinterestAuthAwareTestCase
{
  public function testGet()
  {
    $response = $this->pinterest->sections->get("503066289565421201");

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Section", $response->get(0));
    $this->assertEquals("<BoardSection 5027629787972154693>", $response->get(0)->id);
  }

  public function testPins()
  {
    $response = $this->pinterest->sections->pins("503066289565421201");

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Pin", $response->get(0));
  }

  public function testCreate()
  {
    $response = $this->pinterest->sections->create(
      "503066289565421205",
      array(
        "title" => "Test from API"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Section", $response);
    $this->assertEquals("<BoardSection 5027630990032422748>", $response->id);
  }

  public function testDelete()
  {
    $response = $this->pinterest->sections->delete("5027630990032422748");

    $this->assertTrue($response);
  }
}
