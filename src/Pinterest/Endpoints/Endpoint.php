<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Loggers\RequestLoggerAwareTrait;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Transport\RequestMaker;

class Endpoint
{
  use RequestLoggerAwareTrait;

  protected RequestMaker $request;

  public function __construct(RequestMaker $request, ?RequestLoggerInterface $requestLogger)
  {
    $this->request = $request;

    if ($requestLogger) {
      $this->setRequestLogger($requestLogger);
    }
  }
}
