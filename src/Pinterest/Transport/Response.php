<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

/**
 * @property array $data
 * @property ?string bookmark
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
   * Return the requested key data.
   *
   * @param string $key
   *
   * @return mixed|null
   */
  public function __get(string $key)
  {
    return isset($this->responseData[$key]) ? $this->responseData[$key] : null;
  }

  /**
   * Return if the key is set.
   *
   * @param string $key
   *
   * @return bool
   */
  public function __isset(string $key): bool
  {
    return isset($this->responseData[$key]);
  }

  public function hasBookmark(): bool
  {
    return isset($this->bookmark) && $this->bookmark !== '';
  }

  public function getBookmark(): ?string
  {
    return $this->bookmark;
  }
}
