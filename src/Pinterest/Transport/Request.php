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

use DirkGroenen\Pinterest\Utils\CurlBuilder;
use DirkGroenen\Pinterest\Exceptions\PinterestException;
use DirkGroenen\Pinterest\Exceptions\CurlException;

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
  private string $host = "https://api.pinterest.com/v1/";

  /**
   * Instance of the CurlBuilder class
   *
   * @var CurlBuilder
   */
  private CurlBuilder $curlBuilder;

  /**
   * Array with the headers from the last request
   *
   * @var array
   */
  private $headers;

  /**
   * Constructor
   *
   * @param CurlBuilder $curlBuilder
   */
  public function __construct(CurlBuilder $curlBuilder)
  {
    $this->curlBuilder = $curlBuilder;
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
   * @throws PinterestException|CurlException
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
   * @throws CurlException|PinterestException
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

    // Force cURL to not send Expect header to workaround bug with Akamai CDN not handling
    // this type of requests correctly
    $headers = array_merge(
      $headers,
      array(
        "Expect:",
      )
    );

    // Setup CURL
    $ch = $this->curlBuilder->create();

    // Set default options
    $ch->setOptions(
      array(
        CURLOPT_URL => $apiCall,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HEADER => false,
        CURLINFO_HEADER_OUT => true
      )
    );

    switch ($method) {
      case 'POST':
        $ch->setOptions(
          array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => count($parameters),
            CURLOPT_POSTFIELDS => $parameters
          )
        );

        if (!class_exists('\CURLFile') && defined('CURLOPT_SAFE_UPLOAD')) {
          $ch->setOption(CURLOPT_SAFE_UPLOAD, false);
        } elseif (class_exists('\CURLFile') && defined('CURLOPT_SAFE_UPLOAD')) {
          $ch->setOption(CURLOPT_SAFE_UPLOAD, true);
        }

        break;
      case 'DELETE':
        $ch->setOption(CURLOPT_CUSTOMREQUEST, "DELETE");
        break;
      case 'PATCH':
        $ch->setOptions(
          array(
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POST => count($parameters),
            CURLOPT_POSTFIELDS => $parameters
          )
        );
        break;
      default:
        $ch->setOption(CURLOPT_CUSTOMREQUEST, "GET");
        break;
    }

    // Execute request and catch response
    $response_data = $ch->execute();

    if ($response_data === false && !$ch->hasErrors()) {
      throw new CurlException("Error: Curl request failed");

    } elseif ($ch->hasErrors()) {
      throw new PinterestException(
        'Error: execute() - cURL error: ' . $ch->getErrors(), $ch->getErrorNumber()
      );
    }

    // Initiate the response
    $response = new Response($response_data, $ch);

    // Check the response code
    if ($response->getResponseCode() >= 400) {
      throw new PinterestException(
        'Pinterest error (code: ' . $response->getResponseCode() . ') with message: ' . $response->getMessage(),
        $response->getResponseCode()
      );
    }

    // Get headers from last request
    $this->headers = $ch->getHeaders();

    // Close curl resource
    $ch->close();

    // Return the response
    return $response;
  }

  /**
   * Make a post request to the given endpoint
   *
   * @param string $endpoint
   * @param array $parameters
   * @return Response
   *
   * @throws CurlException|PinterestException
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
   * @throws CurlException|PinterestException
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
   * @throws CurlException|PinterestException
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
   * @throws CurlException|PinterestException
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

  /**
   * Return the headers from the last request
   *
   * @return array
   */
  public function getHeaders()
  {
    return $this->headers;
  }
}
