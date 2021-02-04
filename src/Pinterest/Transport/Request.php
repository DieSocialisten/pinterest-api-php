<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Request
{
  /**
   * Access token
   *
   * @var string|null
   */
  protected ?string $accessTokenValue = null;

  /**
   * Host to make the calls to
   *
   * @var string
   */
  private string $host = "https://api.pinterest.com/v3/";

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
  private function buildFullApiCallUrl(string $apiCall): string
  {
    return $this->host . $apiCall;
  }

  /**
   * Execute the http request
   *
   * @param string $method
   * @param string $apiCall
   * @param array $options
   * @return string
   *
   * @throws HttpClientException
   */
  public function execute(string $method, string $apiCall, array $options = array()): string
  {
    // Check if the access token needs to be added
    $headers = $this->accessTokenValue != null
      ? ["Authorization: Bearer " . $this->accessTokenValue]
      : [];

    $effectiveOptions = array_merge(
      [
        RequestOptions::HEADERS => $headers,
        RequestOptions::CONNECT_TIMEOUT => 20,
        RequestOptions::TIMEOUT => 90,
        RequestOptions::VERIFY => false,

        // TODO leftovers from previous CURL client, need to fine-tune them and left only needed:
        'curl' => [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => false,
          CURLINFO_HEADER_OUT => true
        ]
      ],
      $options
    );

    try {
      $httpResponse = $this->httpClient->request($method, $apiCall, $effectiveOptions);

    } catch (RequestException $e) {
      /** @see https://docs.guzzlephp.org/en/6.5/quickstart.html#exceptions */

      $requestMessage = Message::toString($e->getRequest());
      $responseMessage = $e->hasResponse() ? Message::toString($e->getResponse()) : '';

      throw new HttpClientException('Error: request failed', 0, $e, $requestMessage, $responseMessage);

    } catch (GuzzleException $e) {
      throw new HttpClientException('Error: request failed', 0, $e);
    }

    $this->lastHttpResponse = $httpResponse;

    return (string)$this->lastHttpResponse->getBody();
  }

  public function getLastHttpResponse(): ?ResponseInterface
  {
    return $this->lastHttpResponse;
  }

  /**
   * Make a get request to the given endpoint
   *
   * @param string $endpoint
   * @param array $queryParameters
   * @return string
   *
   * @throws HttpClientException
   */
  public function get(string $endpoint, array $queryParameters = []): string
  {
    $options = [];

    if (!empty($queryParameters)) {
      $options = [RequestOptions::QUERY => $queryParameters];
    }

    return $this->execute("GET", $this->buildFullApiCallUrl($endpoint), $options);
  }

  /**
   * Make a post request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return string
   *
   * @throws HttpClientException
   */
  public function post(string $endpoint, array $parameters = array()): string
  {
    $options = [];

    if (!empty($parameters)) {
      $options = [RequestOptions::FORM_PARAMS => $parameters];
    }

    return $this->execute("POST", $this->buildFullApiCallUrl($endpoint), $options);
  }

  /**
   * Make a put request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return string
   *
   * @throws HttpClientException
   */
  public function put(string $endpoint, array $parameters = array()): string
  {
    $options = [];

    if (!empty($parameters)) {
      $options = [RequestOptions::FORM_PARAMS => $parameters];
    }

    return $this->execute("PUT", $this->buildFullApiCallUrl($endpoint), $options);
  }
}
