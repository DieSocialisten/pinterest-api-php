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

use DirkGroenen\Pinterest\Models\Collection;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
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
    $this->pinterest = PinterestMockFactory::createDefaultPinterestMock($this);
  }

  public function testGet()
  {
    $response = $this->pinterest->pins->get("181692166190246650");

    $this->assertInstanceOf(Pin::class, $response);
    $this->assertEquals("181692166190246650", $response->id);
  }

  public function testFromBoard()
  {
    $response = $this->pinterest->pins->fromBoard("503066289565421201");

    $this->assertInstanceOf(Collection::class, $response);
    $this->assertInstanceOf(Pin::class, $response->get(0));
  }
}
