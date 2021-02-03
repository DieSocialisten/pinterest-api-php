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
use DirkGroenen\Pinterest\Transport\Response;

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
    $endpoint = "pins/{$pinId}/";

    $this->logViaRequestLogger($endpoint, $data);
    $responseBody = $this->request->get($endpoint, $data);

    return new Pin(Response::createFromJson($responseBody));
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
    $endpoint = "boards/{$boardId}/pins/";

    $this->logViaRequestLogger($endpoint, $data);
    $responseBody = $this->request->get($endpoint, $data);

    return new Collection(Response::createFromJson($responseBody), Pin::class);
  }
}
