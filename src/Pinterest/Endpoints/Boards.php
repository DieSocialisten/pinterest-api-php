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

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Models\Board;

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

    $this->logRequest($endpoint, $data);
    $response = $this->request->get($endpoint, $data);

    return new Board($response);
  }
}
