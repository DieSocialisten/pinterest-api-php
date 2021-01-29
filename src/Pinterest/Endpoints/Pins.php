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
use DirkGroenen\Pinterest\Exceptions\InvalidModelException;
use DirkGroenen\Pinterest\Exceptions\InvalidResponseException;
use DirkGroenen\Pinterest\Models\Collection;
use DirkGroenen\Pinterest\Models\Pin;

class Pins extends Endpoint
{
  /**
   * Get a pin object
   *
   * @param string $pinId
   * @param array $data
   * @return Pin
   *
   * @throws HttpClientException
   */
  public function get(string $pinId, array $data = []): Pin
  {
    $endpoint = sprintf("pins/%s/", $pinId);

    $this->parentPinterest->logRequest($endpoint, $data);
    $response = $this->request->get($endpoint, $data);

    return new Pin($this->parentPinterest, $response);
  }

  /**
   * Get all pins from the given board
   *
   * @param string $boardId
   * @param array $data
   * @return Collection
   *
   * @throws HttpClientException|InvalidModelException|InvalidResponseException
   */
  public function fromBoard(string $boardId, array $data = []): Collection
  {
    $endpoint = sprintf("boards/%s/pins/", $boardId);

    $this->parentPinterest->logRequest($endpoint, $data);
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->parentPinterest, $response, Pin::class);
  }
}
