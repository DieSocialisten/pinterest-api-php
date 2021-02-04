<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Loggers\RequestLoggerAwareTrait;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Transport\Request;

class Endpoint
{
  use RequestLoggerAwareTrait;

  protected Request $request;

  public function __construct(Request $request, ?RequestLoggerInterface $requestLogger)
  {
    $this->request = $request;

    if ($requestLogger) {
      $this->setRequestLogger($requestLogger);
    }
  }
}
