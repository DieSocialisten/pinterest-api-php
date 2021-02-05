<?php

declare(strict_types=1);

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
   * @var array
   */
  private array $responseData;

  public function __construct(array $responseData)
  {
    $this->responseData = $responseData;
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
