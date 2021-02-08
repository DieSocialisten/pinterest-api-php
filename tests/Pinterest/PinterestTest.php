<?php

declare(strict_types=1);

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
    $this->pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);
  }

  public function testGetRateLimit()
  {
    $rateLimit = $this->pinterest->getRateLimit();
    $this->assertEquals('1000', $rateLimit);
  }

  public function testGetRateLimitRemaining()
  {
    $rateLimitRemaining = $this->pinterest->getRateLimitRemaining();
    $this->assertEquals('unknown', $rateLimitRemaining);
  }
}
