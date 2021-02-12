<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
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
    $this->assertEquals('https://i.pinimg.com/550x/40/88/ed/4088eda9ce2153483f8dce96d1a50388.jpg', $board->image_cover_url);
    $this->assertEquals('My recipes', $board->name);
    $this->assertEquals('', $board->url);
  }

  private function getResponseBody($filename)
  {
    $fullPath = __DIR__ . '/../responses/BoardsTest/' . $filename;

    return file_get_contents($fullPath);
  }

  private function createPinterestInstanceWithPredefinedResponses(array $responses): Pinterest
  {
    $handlerStack = HandlerStack::create(new MockHandler($responses));

    $client = new Client(['handler' => $handlerStack]);

    return new Pinterest('0', '0', $client);
  }

  /**
   * @test
   */
  public function shouldLoadAllPages()
  {
    $pinterest = $this->createPinterestInstanceWithPredefinedResponses([
      new Response(200, [], $this->getResponseBody('boardsPinsWithPaginationBookmark.json')),
      new Response(200, [], $this->getResponseBody('boardsPinsWithoutPaginationBookmark.json')),
    ]);

    $pageSizeDoesntMatter = 999;

    $pins = $pinterest->boards->pinsAsArray('not important', $pageSizeDoesntMatter, 100);

    self::assertCount(28, $pins);
  }

  /**
   * @test
   *
   * Note: this also an example on usage of "partial" data loading with help of generators.
   *  So we still can have part of the data even if Pinterest API failed
   *
   */
  public function shouldLoadAllPagesUntilExceptionMet()
  {
    $pinterest = $this->createPinterestInstanceWithPredefinedResponses([
      new Response(200, [], $this->getResponseBody('boardsPinsWithPaginationBookmark.json')),
      new Response(400, [], 'No no no, don\'t phunk with my heart'),
      new Response(200, [], $this->getResponseBody('boardsPinsWithoutPaginationBookmark.json')),
    ]);

    $pageSizeDoesntMatter = 999;

    $pins = [];

    try {
      $pinsGenerator = $pinterest->boards->pinsAsGenerator('not important', $pageSizeDoesntMatter, 100);

      foreach ($pinsGenerator as $pin) {
        $pins[] = $pin;
      }

    } catch (\Exception $e) {
      self::assertInstanceOf(PinterestRequestException::class, $e);
    }

    self::assertCount(25, $pins);
  }

  /**
   * @test
   */
  public function shouldRespectMaxNumberOfPagesOption()
  {
    $pinterest = $this->createPinterestInstanceWithPredefinedResponses([
      new Response(200, [], $this->getResponseBody('boardsPinsWithPaginationBookmark.json')),
      new Response(200, [], $this->getResponseBody('boardsPinsWithoutPaginationBookmark.json')),
    ]);

    $pageSizeDoesntMatter = 999;

    $onePageOfPins = $pinterest->boards->pinsAsArray('not important', $pageSizeDoesntMatter, 1);

    self::assertCount(25, $onePageOfPins);
  }

  /**
   * @test
   */
  public function shouldNotContinueLoadingIfPreviousResponseHasNoPaginationBookmark()
  {
    $pinterest = $this->createPinterestInstanceWithPredefinedResponses([
      new Response(200, [], $this->getResponseBody('boardsPinsWithoutPaginationBookmark.json')),
      new Response(200, [], $this->getResponseBody('boardsPinsWithPaginationBookmark.json')),
    ]);

    $pageSizeDoesntMatter = 999;

    $pins = $pinterest->boards->pinsAsArray('not important', $pageSizeDoesntMatter, 100);

    self::assertCount(3, $pins);
  }

  /**
   * @test
   * @responsefile boardsPinsWithoutPaginationBookmark
   */
  public function shouldProperlySetUpBoardPins()
  {
    $pageSizeDoesntMatter = 999;
    $pins = $this->pinterest->boards->pinsAsArray('not important', $pageSizeDoesntMatter, 1);

    /** @var Pin $pin */
    foreach ($pins as $pin) {
      self::assertInstanceOf(Pin::class, $pin);
    }

    $pinA = $pins[0];

    $this->assertEquals("734368282987016445", $pinA->id);
    $this->assertEquals("https://i.pinimg.com/600x/93/15/c3/9315c3be13eb2e7d3a63907dc14648ae.jpg", $pinA->image_large_url);
    $this->assertEquals("Friends | Wallpapers - Imgur", $pinA->description);
    $this->assertEquals("Mon, 25 Jan 2021 15:45:24 +0000", $pinA->created_at);
    $this->assertEquals("https://m.imgur.com/gallery/j2Rcwa5", $pinA->link);
    $this->assertEquals("https://www.pinterest.com/pin/734368282987016445/", $pinA->shareable_url);
  }
}
