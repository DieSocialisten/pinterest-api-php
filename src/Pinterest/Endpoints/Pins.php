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
   * @param string $pinId
   *
   * @throws PinterestRequestException|PinterestDataException
   *
   * @return Pin
   */
  public function get(string $pinId): Pin
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("pins/{$pinId}/");

    $httpResponse = $this->requestMaker->get($endpoint);

    $pin = Pin::create(ResponseFactory::createFromJson($httpResponse));

    if ($pin === false) {
      throw new PinterestDataException("Data for pin {$pinId} is not valid");
    }

    return $pin;
  }
}
