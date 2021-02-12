<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class Pins extends Endpoint
{
  /**
   * Get a pin object
   *
   * @param string $pinId
   * @return Pin
   *
   * @throws PinterestRequestException|PinterestDataException
   */
  public function get(string $pinId): Pin
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("pins/{$pinId}/");

    $httpResponse = $this->requestMaker->get($endpoint);

    return new Pin(ResponseFactory::createFromJson($httpResponse));
  }
}
