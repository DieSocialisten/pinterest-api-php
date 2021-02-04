<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Transport\Response;

class Boards extends Endpoint
{
  /**
   * Find the provided board
   *
   * @param string $boardId
   * @param array $data
   * @return Board
   *
   * @throws HttpClientException
   */
  public function get(string $boardId, array $data = []): Board
  {
    $endpoint = "boards/{$boardId}/";

    $this->logViaRequestLogger($endpoint, $data);
    $responseBody = $this->request->get($endpoint, $data);

    return new Board(Response::createFromJson($responseBody));
  }
}
