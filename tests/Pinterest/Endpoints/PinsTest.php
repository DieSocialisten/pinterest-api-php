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
use \DirkGroenen\Pinterest\Tests\Utils\CurlBuilderMock;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class PinsTest extends TestCase
{
  private Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $curlBuilder = CurlBuilderMock::create($this);

    // Setup Pinterest
    $this->pinterest = new Pinterest("0", "0", $curlBuilder);
    $this->pinterest->auth->setOAuthToken("0");
  }

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

  public function testCreate()
  {
    $response = $this->pinterest->pins->create(
      array(
        "note" => "Test pin from API wrapper",
        "image_url" => "https://download.unsplash.com/photo-1438216983993-cdcd7dea84ce",
        "board" => "503066289565421201"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Pin", $response);
    $this->assertEquals("503066220854919983", $response->id);
  }

  public function testEdit()
  {
    $response = $this->pinterest->pins->edit(
      "503066220854919983",
      array(
        "note" => "Test pin from API wrapper - update"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Pin", $response);
    $this->assertEquals("503066220854919983", $response->id);
  }

  public function testDelete()
  {
    $response = $this->pinterest->pins->delete("503066220854919983");

    $this->assertTrue($response);
  }
}
