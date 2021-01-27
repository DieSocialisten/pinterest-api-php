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

use DirkGroenen\Pinterest\Exceptions\PinterestException;
use DirkGroenen\Pinterest\Exceptions\PinterestExceptionsFactory;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Models\Collection;

class Pins extends Endpoint
{
  /**
   * Get a pin object
   *
   * @param string $pinId
   * @param array $data
   * @return Pin
   *
   * @throws PinterestException
   */
  public function get(string $pinId, array $data = []): Pin
  {
    $endpoint = sprintf("pins/%s/", $pinId);

    try {
      $response = $this->request->get($endpoint, $data);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint);
    }

    return new Pin($this->master, $response);
  }

  /**
   * Get all pins from the given board
   *
   * @param string $boardId
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function fromBoard(string $boardId, array $data = []): Collection
  {
    $endpoint = sprintf("boards/%s/pins/", $boardId);

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Pin::class);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint);
    }
  }
}
