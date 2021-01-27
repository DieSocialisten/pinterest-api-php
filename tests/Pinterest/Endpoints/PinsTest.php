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

class PinsTest extends PinterestAuthAwareTestCase
{
  public function testGet()
  {
    $response = $this->pinterest->pins->get("181692166190246650");

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Pin", $response);
    $this->assertEquals("181692166190246650", $response->id);
  }

  public function testFromBoard()
  {
    $response = $this->pinterest->pins->fromBoard("503066289565421201");

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Pin", $response->get(0));
  }
}
