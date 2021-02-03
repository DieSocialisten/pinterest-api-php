<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Tests;

use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class PinterestTest extends TestCase
{
  private Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $this->pinterest = PinterestMockFactory::createDefaultPinterestMock($this);
  }

  public function testGetRateLimit()
  {
    $rateLimit = $this->pinterest->getRateLimit();
    $this->assertEquals(1000, $rateLimit);
  }

  public function testGetRateLimitRemaining()
  {
    $rateLimitRemaining = $this->pinterest->getRateLimitRemaining();
    $this->assertEquals('unknown', $rateLimitRemaining);
  }
}
