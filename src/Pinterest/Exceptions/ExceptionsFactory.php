<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Exceptions;

use DirkGroenen\Pinterest\Exceptions\PinterestRequest\PinterestAccessTokenException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequest\PinterestThrottlingException;
use GuzzleHttp\Exception\RequestException;

/**
 * @see https://developers.pinterest.com/docs/redoc/pinner_app/#tag/API-Response-Codes
 */
class ExceptionsFactory
{
  private const THROTTLING_ERROR = 429;

  private const ACCESS_TOKEN_ERROR = 401;

  /**
   * @see https://docs.guzzlephp.org/en/6.5/quickstart.html#exceptions
   *
   * @param RequestException $e
   *
   * @return PinterestRequestException
   */
  public static function createPinterestRequestException(RequestException $e): PinterestRequestException
  {
    $response = $e->getResponse();

    switch (true) {
      case $response && $response->getStatusCode() === self::THROTTLING_ERROR:
        return new PinterestThrottlingException('Request failed', $e->getRequest(), $response);

      case $response && $response->getStatusCode() === self::ACCESS_TOKEN_ERROR:
        return new PinterestAccessTokenException('Request failed', $e->getRequest(), $response);

      default:
        return new PinterestRequestException('Request failed', $e->getRequest(), $response);
    }
  }
}
