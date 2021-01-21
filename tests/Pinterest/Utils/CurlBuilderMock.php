<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Tests\Utils;

use DirkGroenen\Pinterest\Utils\CurlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class CurlBuilderMock
{
  /**
   * Create a new mock of the curl builder and return
   * the given filename as content
   *
   * @param TestCase $instance
   * @return MockObject|CurlBuilder
   *
   * @throws ReflectionException
   */
  public static function create(TestCase $instance): MockObject
  {
    $reflection = new \ReflectionMethod($instance, $instance->getName());
    $doc_block = $reflection->getDocComment();

    $responseFile = self::parseDocBlock($doc_block, '@responsefile');
    $responseCode = self::parseDocBlock($doc_block, '@responsecode');

    if (empty($responseCode)) {
      $responseCode = [201];
    }

    if (empty($responseFile)) {
      $responseFile = [$instance->getName()];
    }

    // Setup Curl builder mock
    $curlBuilder = $instance->getMockBuilder("\\DirkGroenen\\Pinterest\\Utils\\CurlBuilder")
      ->getMock();

    $curlBuilder->expects($instance->any())
      ->method('create')
      ->will($instance->returnSelf());

    // Build response file path
    $responseFilePath = __DIR__ . '/../responses/' . (new \ReflectionClass($instance))->getShortName(
      ) . '/' . $responseFile[0] . ".json";

    if (file_exists($responseFilePath)) {
      $curlBuilder->expects($instance->once())
        ->method('execute')
        ->will($instance->returnValue(file_get_contents($responseFilePath)));
    }

    $curlBuilder->expects($instance->any())
      ->method('getInfo')
      ->will($instance->returnValue($responseCode[0]));

    return $curlBuilder;
  }

  /**
   * Parse the methods docblock and search for the
   * requested tag's value
   *
   * @param string $docBlock
   * @param string $tag
   *
   * @return array
   */
  private static function parseDocBlock(string $docBlock, string $tag): array
  {
    $matches = array();

    if (empty($docBlock)) {
      return $matches;
    }

    $regex = "/{$tag} (.*)(\\r\\n|\\r|\\n)/U";
    preg_match_all($regex, $docBlock, $matches);

    if (empty($matches[1])) {
      return array();
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
