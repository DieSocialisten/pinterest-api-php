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

use DirkGroenen\Pinterest\Exceptions\CurlException;
use DirkGroenen\Pinterest\Exceptions\PinterestException;
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
   * @throws CurlException
   */
  public function get(string $pinId, array $data = []): Pin
  {
    $response = $this->request->get(sprintf("pins/%s/", $pinId), $data);

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
   * @throws CurlException
   */
  public function fromBoard(string $boardId, array $data = []): Collection
  {
    $response = $this->request->get(sprintf("boards/%s/pins/", $boardId), $data);

    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Create a pin
   *
   * @param array $data
   * @return Pin
   *
   * @throws PinterestException|CurlException
   */
  public function create(array $data): Pin
  {
    if (array_key_exists("image", $data)) {
      if (class_exists('\CURLFile')) {
        $data["image"] = new \CURLFile($data['image']);
      } else {
        $data["image"] = '@' . $data['image'];
      }
    }

    $response = $this->request->post("pins/", $data);

    return new Pin($this->master, $response);
  }

  /**
   * Edit a pin
   *
   * @param string $pinId
   * @param array $data
   * @param null $fields
   * @return Pin
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function edit(string $pinId, array $data, $fields = null): Pin
  {
    $query = (!$fields) ? [] : ["fields" => $fields];

    $response = $this->request->update(sprintf("pins/%s/", $pinId), $data, $query);

    return new Pin($this->master, $response);
  }

  /**
   * Delete a pin
   *
   * @param string $pinId
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function delete(string $pinId): bool
  {
    $this->request->delete(sprintf("pins/%s/", $pinId));

    return true;
  }
}
