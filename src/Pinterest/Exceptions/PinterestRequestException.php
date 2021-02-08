<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Exceptions;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class PinterestRequestException extends Exception
{
  private ?RequestInterface $request;

  private ?ResponseInterface $response;

  public function __construct(
    $message = '',
    $code = 0,
    Throwable $previous = null,
    ?RequestInterface $request = null,
    ?ResponseInterface $response = null
  )
  {
    parent::__construct($message, $code, $previous);

    $this->request = $request;
    $this->response = $response;
  }

  public function getRequest(): ?RequestInterface
  {
    return $this->request;
  }

  public function getResponse(): ?ResponseInterface
  {
    return $this->response;
  }
}
