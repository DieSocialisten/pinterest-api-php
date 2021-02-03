<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Utils;

use DirkGroenen\Pinterest\Pinterest;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class PinterestMockFactory
{
  /**
   * @param $testCase
   * @return Pinterest
   * @throws ReflectionException
   */
  public static function createDefaultPinterestMock(TestCase $testCase): Pinterest
  {
    $httpClient = HttpClientMockFactory::parseAnnotationsAndCreate($testCase);

    $pinterest = new Pinterest("0", "0", null, $httpClient);
    $pinterest->getAuthComponent()->setAccessTokenValue("0");

    return $pinterest;
  }
}
