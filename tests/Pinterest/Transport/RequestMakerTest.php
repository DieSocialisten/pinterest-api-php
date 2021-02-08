<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Transport;

use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RequestMakerTest extends TestCase
{
  /**
   * @test
   * @dataProvider shouldRaiseAnExceptionInCaseOfAPIErrorDataProvider
   *
   * @param $response
   * @param $exceptionToRaise
   *
   * @throws PinterestRequestException
   */
  public function shouldRaiseAnExceptionInCaseOfAPIError($response, $exceptionToRaise)
  {
    if ($exceptionToRaise) {
      $this->expectException($exceptionToRaise);
    }
    else {
      $this->expectNotToPerformAssertions();
    }

    $mock = new MockHandler([$response]);
    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    (new RequestMaker($client))
      ->get('not important');
  }

  public function shouldRaiseAnExceptionInCaseOfAPIErrorDataProvider(): array
  {
    // indices:
    $response = 0;
    $exceptionToRaise = 1;

    return [
      'No error' => [
        $response => new Response(200, [], ''),
        $exceptionToRaise => null,
      ],

      'Error 400' => [
        $response => new Response(400, [], ''),
        $exceptionToRaise => PinterestRequestException::class,
      ],

      'Error 500' => [
        $response => new Response(500, [], ''),
        $exceptionToRaise => PinterestRequestException::class,
      ],
    ];
  }
}
