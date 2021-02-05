<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class BoardsTest extends TestCase
{
  private const BOARD_ID = '503066289565421201';

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
    $response = $this->pinterest->boards->get(self::BOARD_ID);

    $this->assertInstanceOf(Board::class, $response);
    $this->assertEquals(self::BOARD_ID, $response->id);
  }

  public function testGetWithExtraFields()
  {
    $response = $this->pinterest->boards->get(
      self::BOARD_ID,
      array(
        "fields" => "url,description,creator,counts"
      )
    );

    $this->assertInstanceOf(Board::class, $response);
    $this->assertTrue(isset($response->creator['first_name']));
  }
}
