<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Transport\RequestMaker;

class Endpoint
{
  protected RequestMaker $requestMaker;

  public function __construct(RequestMaker $requestMaker)
  {
    $this->requestMaker = $requestMaker;
  }
}
