<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class HttpClientMockFactory
{
  /**
   * @param TestCase $instance
   *
   * @throws ReflectionException
   *
   * @return Client
   */
  public static function parseAnnotationsAndCreate(TestCase $instance): Client
  {
    $reflection = new ReflectionMethod($instance, $instance->getName());
    $docBlock = $reflection->getDocComment();

    $responseFile = self::parseAnnotationsTag($docBlock, '@responsefile');
    $responseCode = self::parseAnnotationsTag($docBlock, '@responsecode');

    if (empty($responseCode)) {
      $responseCode = [201];
    }

    if (empty($responseFile)) {
      $responseFile = [$instance->getName()];
    }

    // Build response file path
    $responseFilePath = __DIR__ . '/../responses/' . (new ReflectionClass($instance))->getShortName() . '/' .
      $responseFile[0] . '.json';

    if (file_exists($responseFilePath)) {
      $responseData = file_get_contents($responseFilePath);
    } else {
      $responseData = null;
    }

    $mock = new MockHandler([new Response($responseCode[0], [], $responseData)]);
    $handlerStack = HandlerStack::create($mock);

    return new Client(['handler' => $handlerStack]);
  }

  /**
   * Parse the methods docblock and search for the
   * requested tag's value.
   *
   * @param string|false $docBlock
   * @param string       $tag
   *
   * @return array
   */
  private static function parseAnnotationsTag($docBlock, string $tag): array
  {
    $matches = [];

    if (empty($docBlock)) {
      return $matches;
    }

    $regex = "/{$tag} (.*)(\\r\\n|\\r|\\n)/U";
    preg_match_all($regex, $docBlock, $matches);

    if (empty($matches[1])) {
      return [];
    }

    // Removed extra index
    $matches = $matches[1];

    // Trim the results, array item by array item
    foreach ($matches as $ix => $match) {
      $matches[$ix] = trim($match);
    }

    return $matches;
  }
}
