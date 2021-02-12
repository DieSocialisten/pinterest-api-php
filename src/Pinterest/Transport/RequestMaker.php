<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

use DirkGroenen\Pinterest\Exceptions\ExceptionsFactory;
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

  private const API_BASE_URL = 'https://api.pinterest.com/v3/';

  protected ?string $accessTokenValue = null;

  private Client $httpClient;

  public function __construct(Client $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  public function setAccessTokenValue(string $token)
  {
    $this->accessTokenValue = $token;
  }

  public static function buildFullUrlToEndpoint(string $apiCall): string
  {
    return self::API_BASE_URL . $apiCall;
  }

  /**
   * @param string $method
   * @param string $fullUrlToEndpoint
   * @param array $options
   * @return ResponseInterface
   *
   * @throws PinterestRequestException
   */
  private function execute(string $method, string $fullUrlToEndpoint, array $options = array()): ResponseInterface
  {
    // Add access token if it's presented:
    $headers = !is_null($this->accessTokenValue)
      ? ['Authorization' => 'Bearer ' . $this->accessTokenValue]
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

    $this->logViaRequestLogger($fullUrlToEndpoint, $effectiveOptions, $this->accessTokenValue);

    try {
      $httpResponse = $this->httpClient->request($method, $fullUrlToEndpoint, $effectiveOptions);

    } catch (RequestException $e) {
      throw ExceptionsFactory::createPinterestRequestException($e);

    } catch (GuzzleException $e) {
      throw new PinterestRequestException('Request failed with message: ' . $e->getMessage());
    }

    return $httpResponse;
  }

  /**
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
