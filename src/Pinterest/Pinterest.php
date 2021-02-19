<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest;

use DirkGroenen\Pinterest\Auth\PinterestOAuth;
use DirkGroenen\Pinterest\Endpoints\Boards;
use DirkGroenen\Pinterest\Endpoints\Endpoint;
use DirkGroenen\Pinterest\Endpoints\Pins;
use DirkGroenen\Pinterest\Endpoints\Users;
use DirkGroenen\Pinterest\Exceptions\PinterestConfigurationException;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use GuzzleHttp\Client;

/**
 * @property Boards boards
 * @property Pins pins
 * @property Users users
 */
class Pinterest
{
  public const BASE_URL = 'https://www.pinterest.com/';
  public const API_BASE_URL = 'https://api.pinterest.com/v3/';
  public const OAUTH_BASE_URL = 'https://www.pinterest.com/oauth/';
  /**
   * @var Auth\PinterestOAuth
   */
  private PinterestOAuth $auth;

  private RequestMaker $requestMaker;

  private array $cachedEndpoints = [];

  /**
   * @param string  $clientId
   * @param string  $clientSecret
   * @param ?Client $httpClient
   */
  public function __construct(
    string $clientId,
    string $clientSecret,
    ?Client $httpClient = null
  ) {
    if ($httpClient == null) {
      $httpClient = new Client();
    }

    $this->requestMaker = new RequestMaker($httpClient);
    $this->auth = new PinterestOAuth($clientId, $clientSecret, $this->requestMaker);
  }

  public function setRequestLogger(?RequestLoggerInterface $requestLogger)
  {
    $this->requestMaker->setRequestLogger($requestLogger);
  }

  /**
   * Get an Pinterest API endpoint.
   *
   * @param string $endpoint
   *
   * @throws PinterestConfigurationException
   *
   * @return Endpoint
   */
  public function __get(string $endpoint): Endpoint
  {
    $endpointClassname = '\DirkGroenen\Pinterest\Endpoints\\' . ucfirst(strtolower($endpoint));

    if (!isset($this->cachedEndpoints[$endpoint])) {
      if (!class_exists($endpointClassname)) {
        throw new PinterestConfigurationException("Requested endpoint '{$endpoint}' doesn't exist, double-check your code");
      }

      $this->cachedEndpoints[$endpoint] = new $endpointClassname($this->requestMaker);
    }

    return $this->cachedEndpoints[$endpoint];
  }

  /**
   * @return PinterestOAuth
   */
  public function getAuthComponent(): PinterestOAuth
  {
    return $this->auth;
  }
}
