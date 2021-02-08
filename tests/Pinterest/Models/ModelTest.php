<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Models;

use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
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
    $this->pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);
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
