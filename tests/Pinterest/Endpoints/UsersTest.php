<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
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
    $this->pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);
  }

  /**
   * @test
   * @responsefile me
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   */
  public function shouldMapResponseToUserModelProperly()
  {
    $user = $this->pinterest->users->me();

    $this->assertInstanceOf(User::class, $user);

    $this->assertEquals("734368420396834766", $user->getId());
    $this->assertEquals("vladimirpwalls", $user->getUsername());
    $this->assertEquals("Vladimir Pwalls", $user->getFullName());
    $this->assertEquals("https://s.pinimg.com/images/user/default_60.png", $user->getImageUrl());
  }
}
