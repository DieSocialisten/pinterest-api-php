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
   *
   * @throws ReflectionException
   *
   * @return Pinterest
   */
  public static function parseAnnotationsAndCreatePinterestMock(TestCase $testCase): Pinterest
  {
    $httpClientConfig = HttpClientMockFactory::parseAnnotationsAndCreate($testCase);

    $pinterest = new Pinterest('0', '0', $httpClientConfig);
    $pinterest->getAuthComponent()->setAccessTokenValue('0');

    return $pinterest;
  }

  /**
   * @param TestCase               $testCase
   * @param RequestLoggerInterface $requestLogger
   *
   * @throws ReflectionException
   *
   * @return Pinterest
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
