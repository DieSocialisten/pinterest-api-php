<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Collection;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class Pins extends Endpoint
{
  /**
   * Get a pin object
   *
   * @param string $pinId
   * @param array $data
   * @return Pin
   *
   * @throws PinterestRequestException|PinterestDataException
   */
  public function get(string $pinId, array $data = []): Pin
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("pins/{$pinId}/");

    $httpResponse = $this->requestMaker->get($endpoint, $data);

    return new Pin(ResponseFactory::createFromJson($httpResponse));
  }

  /**
   * Get all pins from the given board
   *
   * @param string $boardId
   * @param array $data
   * @return Collection
   *
   * @throws PinterestRequestException|PinterestDataException
   */
  public function fromBoard(string $boardId, array $data = []): Collection
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("boards/{$boardId}/pins/");

    $httpResponse = $this->requestMaker->get($endpoint, $data);

    return new Collection(ResponseFactory::createFromJson($httpResponse), Pin::class);
  }
}
