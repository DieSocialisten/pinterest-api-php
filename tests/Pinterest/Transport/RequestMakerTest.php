<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Transport;

use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Transport\RequestMaker;
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
   * @param array $responses
   * @param $exceptionToRaise
   *
   * @throws PinterestRequestException
   */
  public function shouldRaiseAnExceptionInCaseOfAPIError(array $responses, $exceptionToRaise)
  {
    if ($exceptionToRaise) {
      $this->expectException($exceptionToRaise);
    } else {
      $this->expectNotToPerformAssertions();
    }

    $mock = new MockHandler($responses);
    $handlerStack = HandlerStack::create($mock);

    (new RequestMaker(['handler' => $handlerStack]))
      ->get('not important');
  }

  public function shouldRaiseAnExceptionInCaseOfAPIErrorDataProvider(): array
  {
    // indices:
    $responses = 0;
    $exceptionToRaise = 1;

    return [
      'No error' => [
        $responses => [new Response(200, [], '')],
        $exceptionToRaise => null,
      ],

      'Error 400, then error 401' => [
        $responses => [
          new Response(400, [], ''),
          new Response(401, [], ''),
        ],
        $exceptionToRaise => PinterestRequestException::class,
      ],

      'Error 401: retried request is successful' => [
        $responses => [
          new Response(401, [], ''),
          new Response(200, [], ''),
        ],
        $exceptionToRaise => null,
      ],

      'Error 401: retried request has also 401 status' => [
        $responses => [
          new Response(401, [], ''),
          new Response(401, [], ''),
        ],
        $exceptionToRaise => PinterestRequestException::class,
      ],

      'Error 401: retried request has 500 status' => [
        $responses => [
          new Response(401, [], ''),
          new Response(500, [], ''),
        ],
        $exceptionToRaise => PinterestRequestException::class,
      ],

      'Error 500' => [
        $responses => [new Response(500, [], '')],
        $exceptionToRaise => PinterestRequestException::class,
      ],
    ];
  }
}
