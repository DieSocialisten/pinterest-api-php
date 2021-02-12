<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\Response;
use DirkGroenen\Pinterest\Transport\ResponseFactory;
use Generator;

class Boards extends Endpoint
{
  /**
   * Find the provided board
   *
   * @param string $boardId
   * @return Board
   *
   * @throws PinterestRequestException|PinterestDataException
   */
  public function get(string $boardId): Board
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("boards/{$boardId}/");

    $httpResponse = $this->requestMaker->get($endpoint);

    return new Board(ResponseFactory::createFromJson($httpResponse));
  }

  /**
   * Get all pins from the given board
   *
   * @param string $boardId
   * @param int $pageSize
   * @param int $maxNumberOfPages
   *
   * @return Generator
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   */
  public function pinsAsGenerator(string $boardId, int $pageSize, int $maxNumberOfPages): Generator
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("boards/{$boardId}/pins/");

    /** @var Response $response */
    foreach ($this->getAllPages($maxNumberOfPages, $endpoint, ['page_size' => $pageSize]) as $response) {
      if (isset($response->data)) {
        foreach ($response->data as $pinData) {
          yield new Pin($pinData);
        }
      }
    }
  }

  /**
   * @param string $boardId
   * @param int $pageSize
   * @param int $maxNumberOfPages
   *
   * @return array
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   */
  public function pinsAsArray(string $boardId, int $pageSize, int $maxNumberOfPages): array
  {
    return iterator_to_array($this->pinsAsGenerator($boardId, $pageSize, $maxNumberOfPages));
  }
}
