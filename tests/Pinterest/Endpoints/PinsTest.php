<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
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
    $this->pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);
  }

  /**
   * @test
   * @responsefile get
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   */
  public function shouldMapResponseToPinModelProperly()
  {
    $pin = $this->pinterest->pins->get("doesn't matter");

    $this->assertInstanceOf(Pin::class, $pin);
    $this->assertEquals("547046685988132022", $pin->id);
  }
}
