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

class ModelTest extends TestCase
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

  /**
   * @responsefile    pin
   */
  public function testIfPinDecodesToJson()
  {
    $response = $this->pinterest->pins->get("181692166190246650");

    $this->assertTrue(is_string($response->toJson()));
  }

  /**
   * @responsefile    pin
   */
  public function testIfPinConvertsToArray()
  {
    $response = $this->pinterest->pins->get("181692166190246650");

    $this->assertTrue(is_array($response->toArray()));
  }
}
