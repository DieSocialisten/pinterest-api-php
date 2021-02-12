<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class BoardsTest extends TestCase
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
  public function shouldMapResponseToBoardModelProperly()
  {
    $board = $this->pinterest->boards->get("doesn't matter");

    $this->assertInstanceOf(Board::class, $board);
    $this->assertEquals('549755885175', $board->id);
  }
}
