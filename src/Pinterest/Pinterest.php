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
use DirkGroenen\Pinterest\Endpoints\Following;
use DirkGroenen\Pinterest\Endpoints\Pins;
use DirkGroenen\Pinterest\Endpoints\Sections;
use DirkGroenen\Pinterest\Endpoints\Users;
use DirkGroenen\Pinterest\Loggers\ErrorLoggerInterface;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Utils\CurlBuilder;
use DirkGroenen\Pinterest\Transport\Request;
use DirkGroenen\Pinterest\Exceptions\InvalidEndpointException;

/**
 * @property Boards boards
 * @property Following following
 * @property Pins pins
 * @property Users users
 * @property Sections sections
 */
class Pinterest
{
  /**
   * Reference to authentication class instance
   *
   * @var Auth\PinterestOAuth
   */
  public PinterestOAuth $auth;

  /**
   * A reference to the request class which travels
   * through the application
   *
   * @var Transport\Request
   */
  public Request $request;

  /**
   * A array containing the cached endpoints
   *
   * @var array
   */
  private array $cachedEndpoints = [];

  private ?ErrorLoggerInterface $errorLogger = null;

  private ?RequestLoggerInterface $requestLogger = null;

  /**
   * Constructor
   *
   * @param string $clientId
   * @param string $clientSecret
   * @param CurlBuilder|null $curlBuilder
   */
  public function __construct(string $clientId, string $clientSecret, ?CurlBuilder $curlBuilder = null)
  {
    if ($curlBuilder == null) {
      $curlBuilder = new CurlBuilder();
    }

    // Create new instance of Transport\Request
    $this->request = new Request($curlBuilder);

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
      $ref = new \ReflectionClass($class);
      $obj = $ref->newInstanceArgs([$this->request, $this]);

      $this->cachedEndpoints[$endpoint] = $obj;
    }

    return $this->cachedEndpoints[$endpoint];
  }

  /**
   * Get rate limit from the headers
   * response header may change from X-Ratelimit-Limit to X-RateLimit-Limit
   *
   * @return int|string
   */
  public function getRateLimit()
  {
    $header = $this->request->getHeaders();

    if (is_array($header)) {
      $header = array_change_key_case($header, CASE_LOWER);
    }

    return (isset($header['x-ratelimit-limit']) ? $header['x-ratelimit-limit'] : 1000);
  }

  /**
   * Get rate limit remaining from the headers
   * response header may change from X-Ratelimit-Remaining to X-RateLimit-Remaining
   *
   * @return mixed
   */
  public function getRateLimitRemaining()
  {
    $header = $this->request->getHeaders();

    if (is_array($header)) {
      $header = array_change_key_case($header, CASE_LOWER);
    }

    return (isset($header['x-ratelimit-remaining']) ? $header['x-ratelimit-remaining'] : 'unknown');
  }

  public function setErrorLogger(?ErrorLoggerInterface $errorLogger): Pinterest
  {
    $this->errorLogger = $errorLogger;

    return $this;
  }

  public function logError(array $data)
  {
    if (!$this->errorLogger) {
      return;
    }

    $this->errorLogger->log($data);
  }

  public function setRequestLogger(?RequestLoggerInterface $requestLogger): Pinterest
  {
    $this->requestLogger = $requestLogger;

    return $this;
  }

  public function logRequest(array $data)
  {
    if (!$this->requestLogger) {
      return;
    }

    $this->requestLogger->log($data);
  }
}
