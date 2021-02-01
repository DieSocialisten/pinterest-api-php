<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
