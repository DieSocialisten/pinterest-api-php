<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Models\User;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class UsersTest extends TestCase
{
  private Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $this->pinterest = PinterestMockFactory::createDefaultPinterestMock($this);
  }

  public function testMe()
  {
    $response = $this->pinterest->users->me();

    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals("503066358284560467", $response->id);
  }
}
