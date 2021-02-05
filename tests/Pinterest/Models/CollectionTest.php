<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Models;

use DirkGroenen\Pinterest\Models\Collection;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class CollectionTest extends TestCase
{
  private const ID_DOESNT_MATTER = '123';

  private Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $this->pinterest = PinterestMockFactory::createDefaultPinterestMock($this);
  }

  /**
   * @responsefile pinsFromBoard
   */
  public function testIfCollectionAllReturnsItems()
  {
    $response = $this->pinterest->pins->fromBoard(self::ID_DOESNT_MATTER);

    $this->assertInstanceOf(Collection::class, $response);
    $this->assertTrue(is_array($response->all()));
  }

  /**
   * @responsefile pinsFromBoard
   */
  public function testIfCollectionGetReturnsCorrectPin()
  {
    $response = $this->pinterest->pins->fromBoard(self::ID_DOESNT_MATTER);

    $this->assertInstanceOf(Collection::class, $response);
    $this->assertInstanceOf(Pin::class, $response->get(1));
    $this->assertEquals("503066220854919488", $response->get(1)->id);
  }

  /**
   * @responsefile pinsFromBoard
   */
  public function testIfCollectionHasNextPage()
  {
    $response = $this->pinterest->pins->fromBoard(self::ID_DOESNT_MATTER);

    $this->assertTrue($response->hasNextPage());
  }

  /**
   * @responsefile pinsFromBoard
   */
  public function testIfCollectionDecodesToJson()
  {
    $response = $this->pinterest->pins->fromBoard(self::ID_DOESNT_MATTER);

    $this->assertTrue(is_string($response->toJson()));
  }

  /**
   * @responsefile pinsFromBoard
   */
  public function testIfCollectionDecodesToArray()
  {
    $response = $this->pinterest->pins->fromBoard(self::ID_DOESNT_MATTER);

    $this->assertTrue(is_array($response->toArray()));
  }
}
