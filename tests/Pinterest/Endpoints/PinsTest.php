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

    $this->assertEquals('734368282987016445', $pin->getId());
    $this->assertEquals('https://i.pinimg.com/600x/93/15/c3/9315c3be13eb2e7d3a63907dc14648ae.jpg', $pin->getImageUrl());
    $this->assertEquals('Friends | Wallpapers - Imgur', $pin->getDescription());
    $this->assertEquals('Mon, 25 Jan 2021 15:45:24 +0000', $pin->getCreatedAt());
    $this->assertEquals('https://m.imgur.com/gallery/j2Rcwa5', $pin->getLink());
    $this->assertEquals('https://www.pinterest.com/pin/734368282987016445/', $pin->getShareableUrl());
  }
}
