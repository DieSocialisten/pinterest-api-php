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
   * @var array
   */
  private array $responseData;

  public function __construct(string $json)
  {
    $this->responseData = $this->decodeString($json);
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
    return isset($this->responseData[$key]) ? $this->responseData[$key] : [];
  }

  /**
   * Return if the key is set
   *
   * @param string $key
   * @return bool
   */
  public function __isset(string $key): bool
  {
    return isset($this->responseData[$key]);
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
    return isset($this->message) ? $this->message : $this->error;
  }
}
