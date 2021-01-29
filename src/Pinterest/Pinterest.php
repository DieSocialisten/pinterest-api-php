<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest;

use DirkGroenen\Pinterest\Auth\PinterestOAuth;
use DirkGroenen\Pinterest\Endpoints\Boards;
use DirkGroenen\Pinterest\Endpoints\Pins;
use DirkGroenen\Pinterest\Endpoints\Users;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Transport\Request;
use DirkGroenen\Pinterest\Exceptions\InvalidEndpointException;
use GuzzleHttp\Client;
use ReflectionClass;

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

  private ?RequestLoggerInterface $requestLogger = null;

  /**
   * @param string $clientId
   * @param string $clientSecret
   * @param ?Client $httpClient
   */
  public function __construct(string $clientId, string $clientSecret, ?Client $httpClient = null)
  {
    if ($httpClient == null) {
      $httpClient = new Client();
    }

    // Create new instance of Transport\Request
    $this->request = new Request($httpClient);

    // Create and set new instance of the OAuth class
    $this->auth = new PinterestOAuth($clientId, $clientSecret, $this->request);
  }

  /**
   * Get an Pinterest API endpoint
   *
   * @param string $endpoint
   * @return mixed
   * @throws Exceptions\InvalidEndpointException|\ReflectionException
   */
  public function __get(string $endpoint)
  {
    $endpoint = strtolower($endpoint);

    $class = "\\DirkGroenen\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

    // Check if an instance has already been initiated
    if (!isset($this->cachedEndpoints[$endpoint])) {
      // Check endpoint existence
      if (!class_exists($class)) {
        throw new InvalidEndpointException();
      }

      // Create a reflection of the called class and initialize it
      // with a reference to the request class
      $ref = new ReflectionClass($class);
      $obj = $ref->newInstanceArgs([$this->request, $this]);

      $this->cachedEndpoints[$endpoint] = $obj;
    }

    return $this->cachedEndpoints[$endpoint];
  }

  private function getHeaderValueOrUseFallback(string $headerName, $fallbackValue = null): ?string
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
    return $this->getHeaderValueOrUseFallback('x-ratelimit-limit', 1000);
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

  public function setRequestLogger(?RequestLoggerInterface $requestLogger): Pinterest
  {
    $this->requestLogger = $requestLogger;

    return $this;
  }

  public function logRequest(string $endpoint, array $payload)
  {
    if (!$this->requestLogger) {
      return;
    }

    $this->requestLogger->log($endpoint, $payload);
  }

  /**
   * @return PinterestOAuth
   */
  public function getAuthComponent(): PinterestOAuth
  {
    return $this->auth;
  }
}
