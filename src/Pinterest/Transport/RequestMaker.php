<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Loggers\RequestLoggerAwareInterface;
use DirkGroenen\Pinterest\Loggers\RequestLoggerAwareTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class RequestMaker implements RequestLoggerAwareInterface
{
  use RequestLoggerAwareTrait;

  /**
   * @var string
   */
  private const API_BASE_URL = 'https://api.pinterest.com/v3/';

  /**
   * Access token
   *
   * @var string|null
   */
  protected ?string $accessTokenValue = null;

  /**
   * @var Client
   */
  private Client $httpClient;

  private ?ResponseInterface $lastHttpResponse = null;

  public function __construct(Client $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Set the access token
   *
   * @param string $token
   */
  public function setAccessTokenValue(string $token)
  {
    $this->accessTokenValue = $token;
  }

  /**
   * @param string $apiCall
   * @return string
   */
  public static function buildFullUrlToEndpoint(string $apiCall): string
  {
    return self::API_BASE_URL . $apiCall;
  }

  /**
   * Execute the http request
   *
   * @param string $method
   * @param string $fullUrlToEndpoint
   * @param array $options
   * @return ResponseInterface
   *
   * @throws PinterestRequestException
   */
  private function execute(string $method, string $fullUrlToEndpoint, array $options = array()): ResponseInterface
  {
    // Check if the access token needs to be added
    $headers = $this->accessTokenValue != null
      ? ['Authorization: Bearer ' . $this->accessTokenValue]
      : [];

    $effectiveOptions = array_merge(
      [
        RequestOptions::HEADERS => $headers,
        RequestOptions::CONNECT_TIMEOUT => 20,
        RequestOptions::TIMEOUT => 90,
        RequestOptions::VERIFY => false,
        RequestOptions::HTTP_ERRORS => true,
      ],
      $options
    );

    $this->lastHttpResponse = null;

    $this->logViaRequestLogger($fullUrlToEndpoint, $effectiveOptions);

    try {
      $httpResponse = $this->httpClient->request($method, $fullUrlToEndpoint, $effectiveOptions);

    } catch (RequestException $e) {
      /** @see https://docs.guzzlephp.org/en/6.5/quickstart.html#exceptions */

      throw new PinterestRequestException('Request failed', $e->getRequest(), $e->getResponse());

    } catch (GuzzleException $e) {
      throw new PinterestRequestException('Request failed with message: ' . $e->getMessage());
    }

    $this->lastHttpResponse = $httpResponse;

    return $this->lastHttpResponse;
  }

  public function getLastHttpResponse(): ?ResponseInterface
  {
    return $this->lastHttpResponse;
  }

  /**
   * Make a get request to the given endpoint
   *
   * @param string $fullUrlToEndpoint
   * @param array $queryParameters
   * @return ResponseInterface
   *
   * @throws PinterestRequestException
   */
  public function get(string $fullUrlToEndpoint, array $queryParameters = []): ResponseInterface
  {
    $options = [];

    if (!empty($queryParameters)) {
      $options = [RequestOptions::QUERY => $queryParameters];
    }

    return $this->execute('GET', $fullUrlToEndpoint, $options);
  }

  /**
   * Make a post request to the given endpoint
   *
   * @param string $fullUrlToEndpoint
   * @param array $parameters
   * @return ResponseInterface
   *
   * @throws PinterestRequestException
   */
  public function post(string $fullUrlToEndpoint, array $parameters = array()): ResponseInterface
  {
    $options = [];

    if (!empty($parameters)) {
      $options = [RequestOptions::FORM_PARAMS => $parameters];
    }

    return $this->execute('POST', $fullUrlToEndpoint, $options);
  }

  /**
   * Make a put request to the given endpoint
   *
   * @param string $fullUrlToEndpoint
   * @param array $parameters
   * @return ResponseInterface
   *
   * @throws PinterestRequestException
   */
  public function put(string $fullUrlToEndpoint, array $parameters = array()): ResponseInterface
  {
    $options = [];

    if (!empty($parameters)) {
      $options = [RequestOptions::FORM_PARAMS => $parameters];
    }

    return $this->execute('PUT', $fullUrlToEndpoint, $options);
  }
}
