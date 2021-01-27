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

use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Tests\PinterestAuthAwareTestCase;

class BoardsTest extends PinterestAuthAwareTestCase
{
  private const BOARD_ID = '503066289565421201';

  public function testGet()
  {
    $response = $this->pinterest->boards->get(self::BOARD_ID);

    $this->assertInstanceOf(Board::class, $response);
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

    $this->assertInstanceOf(Board::class, $response);
    $this->assertTrue(isset($response->creator['first_name']));
  }
}
