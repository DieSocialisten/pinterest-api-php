<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class Boards extends Endpoint
{
  /**
   * Find the provided board
   *
   * @param string $boardId
   * @param array $data
   * @return Board
   *
   * @throws PinterestRequestException|PinterestDataException
   */
  public function get(string $boardId, array $data = []): Board
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("boards/{$boardId}/");

    $httpResponse = $this->requestMaker->get($endpoint, $data);

    return new Board(ResponseFactory::createFromJson($httpResponse));
  }
}
