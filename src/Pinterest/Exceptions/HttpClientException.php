<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Exceptions;

use Throwable;

class HttpClientException extends \Exception
{
  private string $requestMessage;
  private string $responseMessage;

  public function __construct($message = "", $code = 0, Throwable $previous = null, string $requestMessage = '', string $responseMessage = '')
  {
    parent::__construct($message, $code, $previous);

    $this->requestMessage = $requestMessage;
    $this->responseMessage = $responseMessage;
  }

  /**
   * @return string
   */
  public function getRequestMessage(): string
  {
    return $this->requestMessage;
  }

  /**
   * @return string
   */
  public function getResponseMessage(): string
  {
    return $this->responseMessage;
  }
}
