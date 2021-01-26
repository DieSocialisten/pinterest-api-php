<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Transport;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;

class Request
{
  /**
   * Access token
   *
   * @var string|null
   */
  protected ?string $accessToken = null;

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

  private ?\GuzzleHttp\Psr7\Response $lastHttpResponse = null;

  public function __construct(Client $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Set the access token
   *
   * @param string $token
   */
  public function setAccessToken(string $token)
  {
    $this->accessToken = $token;
  }

  /**
   * Make a get request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return Response
   *
   * @throws HttpClientException
   */
  public function get(string $endpoint, array $parameters = []): Response
  {
    if (!empty($parameters)) {
      $path = sprintf("%s?%s", $endpoint, http_build_query($parameters));
    } else {
      $path = $endpoint;
    }

    return $this->execute("GET", sprintf("%s%s", $this->host, $path));
  }

  /**
   * Execute the http request
   *
   * @param string $method
   * @param string $apiCall
   * @param array $parameters
   * @param array $headers
   * @return Response
   *
   * @throws HttpClientException
   */
  public function execute(string $method, string $apiCall, array $parameters = array(), $headers = array()): Response
  {
    // Check if the access token needs to be added
    if ($this->accessToken != null) {
      $headers = array_merge(
        $headers,
        array(
          "Authorization: Bearer " . $this->accessToken,
        )
      );
    }

    try {
      $httpResponse = $this->httpClient->request(
        $method,
        $apiCall,
        [
          RequestOptions::HEADERS => $headers,
          RequestOptions::CONNECT_TIMEOUT => 20,
          RequestOptions::TIMEOUT => 90,
          RequestOptions::VERIFY => false,

          'curl' => [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLINFO_HEADER_OUT => true
          ]
        ]
      );
    } catch (RequestException $e) {
      /** @see https://docs.guzzlephp.org/en/stable/quickstart.html#exceptions */

      $requestMessage = Message::toString($e->getRequest());
      $responseMessage = $e->hasResponse() ? Message::toString($e->getResponse()) : '';

      throw new HttpClientException('Error: request failed', 0, $e, $requestMessage, $responseMessage);

    } catch (GuzzleException $e) {
      throw new HttpClientException('Error: request failed', 0, $e);
    }

    $this->lastHttpResponse = $httpResponse;

    return new Response((string)$this->lastHttpResponse->getBody());
  }

  public function getLastHttpResponse(): ?\GuzzleHttp\Psr7\Response
  {
    return $this->lastHttpResponse;
  }

  /**
   * Make a post request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return Response
   *
   * @throws HttpClientException
   */
  public function post(string $endpoint, array $parameters = array()): Response
  {
    return $this->execute("POST", sprintf("%s%s", $this->host, $endpoint), $parameters);
  }

  /**
   * Make a put request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return Response
   *
   * @throws HttpClientException
   */
  public function put(string $endpoint, array $parameters = array()): Response
  {
    return $this->execute("PUT", sprintf("%s%s", $this->host, $endpoint), $parameters);
  }

  /**
   * Make a delete request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return Response
   *
   * @throws HttpClientException
   */
  public function delete(string $endpoint, array $parameters = array()): Response
  {
    return $this->execute("DELETE", sprintf("%s%s", $this->host, $endpoint) . "/", $parameters);
  }

  /**
   * Make an update request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @param array $queryParameters
   * @return Response
   *
   * @throws HttpClientException
   */
  public function update(string $endpoint, array $parameters = array(), array $queryParameters = array()): Response
  {
    if (!empty($queryParameters)) {
      $path = sprintf("%s?%s", $endpoint, http_build_query($queryParameters));
    } else {
      $path = $endpoint;
    }

    return $this->execute("PATCH", sprintf("%s%s", $this->host, $path), $parameters);
  }
}
