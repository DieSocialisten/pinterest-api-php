<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest;

use DirkGroenen\Pinterest\Auth\PinterestOAuth;
use DirkGroenen\Pinterest\Endpoints\Boards;
use DirkGroenen\Pinterest\Endpoints\Endpoint;
use DirkGroenen\Pinterest\Endpoints\Pins;
use DirkGroenen\Pinterest\Endpoints\Users;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Transport\Request;
use DirkGroenen\Pinterest\Exceptions\InvalidEndpointException;
use GuzzleHttp\Client;

/**
 * @property Boards boards
 * @property Pins pins
 * @property Users users
 */
class Pinterest
{
  /**
   * @var Auth\PinterestOAuth
   */
  private PinterestOAuth $auth;

  /**
   * A reference to the request class which travels
   * through the application
   *
   * @var Transport\Request
   */
  public Request $request;

  /**
   * @var array
   */
  private array $cachedEndpoints = [];

  /**
   * @var RequestLoggerInterface|null
   */
  private ?RequestLoggerInterface $requestLogger;

  /**
   * @param string $clientId
   * @param string $clientSecret
   * @param RequestLoggerInterface|null $requestLogger
   * @param ?Client $httpClient
   */
  public function __construct(
    string $clientId,
    string $clientSecret,
    ?RequestLoggerInterface $requestLogger = null,
    ?Client $httpClient = null
  )
  {
    if ($httpClient == null) {
      $httpClient = new Client();
    }

    $this->request = new Request($httpClient);
    $this->auth = new PinterestOAuth($clientId, $clientSecret, $this->request);
    $this->requestLogger = $requestLogger;
  }

  /**
   * Get an Pinterest API endpoint
   *
   * @param string $endpoint
   * @return Endpoint
   *
   * @throws InvalidEndpointException
   */
  public function __get(string $endpoint): Endpoint
  {
    $endpointClassname = "\\DirkGroenen\\Pinterest\\Endpoints\\" . ucfirst(strtolower($endpoint));

    if (!isset($this->cachedEndpoints[$endpoint])) {
      if (!class_exists($endpointClassname)) {
        throw new InvalidEndpointException();
      }

      $this->cachedEndpoints[$endpoint] = new $endpointClassname($this->request, $this->requestLogger);
    }

    return $this->cachedEndpoints[$endpoint];
  }

  private function getHeaderValueOrUseFallback(string $headerName, ?string $fallbackValue): ?string
  {
    $lastResponse = $this->request->getLastHttpResponse();

    if (!$lastResponse) {
      return $fallbackValue;
    }

    return $lastResponse->hasHeader($headerName)
      ? $lastResponse->getHeaderLine($headerName)
      : $fallbackValue;
  }

  /**
   * Get rate limit from the headers
   * response header may change from X-Ratelimit-Limit to X-RateLimit-Limit
   *
   * @return int|string
   */
  public function getRateLimit()
  {
    return $this->getHeaderValueOrUseFallback('x-ratelimit-limit', '1000');
  }

  /**
   * Get rate limit remaining from the headers
   * response header may change from X-Ratelimit-Remaining to X-RateLimit-Remaining
   *
   * @return string
   */
  public function getRateLimitRemaining(): string
  {
    return $this->getHeaderValueOrUseFallback('x-ratelimit-remaining', 'unknown');
  }

  /**
   * @return PinterestOAuth
   */
  public function getAuthComponent(): PinterestOAuth
  {
    return $this->auth;
  }
}
