<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Utils;

use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
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
  public static function parseAnnotationsAndCreatePinterestMock(TestCase $testCase): Pinterest
  {
    $httpClient = HttpClientMockFactory::parseAnnotationsAndCreate($testCase);

    $pinterest = new Pinterest("0", "0", $httpClient);
    $pinterest->getAuthComponent()->setAccessTokenValue("0");

    return $pinterest;
  }

  /**
   * @param TestCase $testCase
   * @param RequestLoggerInterface $requestLogger
   * @return Pinterest
   * @throws ReflectionException
   */
  public static function createLoggerAwarePinterestMock(
    TestCase $testCase,
    RequestLoggerInterface $requestLogger
  ): Pinterest {
    $pinterest = self::parseAnnotationsAndCreatePinterestMock($testCase);
    $pinterest->setRequestLogger($requestLogger);

    return $pinterest;
  }
}