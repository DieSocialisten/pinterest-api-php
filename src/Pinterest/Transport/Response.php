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

/**
 * @property array $page
 * @property array $data
 * @property string $message
 * @property string $error
 */
class Response
{
  /**
   * Contains the raw response
   *
   * @var string|array
   */
  private $response;

  /**
   * Used curl instance
   *
   * @var CurlBuilder
   */
  private CurlBuilder $curl;

  /**
   * Constructor
   *
   * @param string|array $response
   * @param CurlBuilder $curl
   */
  public function __construct($response, CurlBuilder $curl)
  {
    $this->response = $response;
    $this->curl = $curl;

    if (is_string($response)) {
      $this->response = $this->decodeString($response);
    }
  }

  /**
   * Decode the string to an array
   *
   * @param string $response
   * @return array
   */
  private function decodeString(string $response): array
  {
    return json_decode($response, true);
  }

  /**
   * Return the requested key data
   *
   * @param string $key
   * @return mixed
   */
  public function __get(string $key)
  {
    return isset($this->response[$key]) ? $this->response[$key] : [];
  }

  /**
   * Return if the key is set
   *
   * @param string $key
   * @return bool
   */
  public function __isset(string $key): bool
  {
    return isset($this->response[$key]);
  }

  /**
   * Returns the error message which should normally
   * by located in the response->message key, but can
   * also be localed in the response->error key.
   *
   * @return string
   */
  public function getMessage(): string
  {
    return (isset($this->message)) ? $this->message : $this->error;
  }

  /**
   * Get the response code from the request
   *
   * @return numeric|string
   */
  public function getResponseCode()
  {
    return $this->curl->getInfo(CURLINFO_HTTP_CODE);
  }
}
