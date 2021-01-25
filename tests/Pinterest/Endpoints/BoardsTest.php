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

class BoardsTest extends PinterestAuthAwareTestCase
{
  private const BOARD_ID = '503066289565421201';

  public function testGet()
  {
    $response = $this->pinterest->boards->get(self::BOARD_ID);

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Board", $response);
    $this->assertEquals(self::BOARD_ID, $response->id);
  }

  public function testGetWithExtraFields()
  {
    $response = $this->pinterest->boards->get(
      self::BOARD_ID,
      array(
        "fields" => "url,description,creator,counts"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Board", $response);
    $this->assertTrue(isset($response->creator['first_name']));
  }

  public function testCreate()
  {
    $response = $this->pinterest->boards->create(
      array(
        "name" => "Test board from API",
        "description" => "Test Board From API Test"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Board", $response);
    $this->assertEquals("503066289565421205", $response->id);
  }

  public function testEdit()
  {
    $response = $this->pinterest->boards->edit(
      self::BOARD_ID,
      array(
        "name" => "Test board from API"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Board", $response);
    $this->assertEquals("503066289565421205", $response->id);
  }

  public function testDelete()
  {
    $response = $this->pinterest->boards->delete("503066289565421205");

    $this->assertTrue($response);
  }
}
