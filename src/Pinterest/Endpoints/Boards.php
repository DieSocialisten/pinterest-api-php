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
   * @throws PinterestException
   * @throws CurlException
   */
  public function get(string $boardId, array $data = []): Board
  {
    $response = $this->request->get(sprintf("boards/%s/", $boardId), $data);

    return new Board($this->master, $response);
  }

  /**
   * Create a new board
   *
   * @param array $data
   * @return Board
   *
   * @throws PinterestException|CurlException
   */
  public function create(array $data): Board
  {
    $response = $this->request->post("boards/", $data);

    return new Board($this->master, $response);
  }

  /**
   * Edit a board
   *
   * @param string $boardId
   * @param array $data
   * @param null $fields
   * @return Board
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function edit(string $boardId, array $data, $fields = null): Board
  {
    $query = (!$fields) ? [] : ["fields" => $fields];

    $response = $this->request->update(sprintf("boards/%s/", $boardId), $data, $query);

    return new Board($this->master, $response);
  }

  /**
   * Delete a board
   *
   * @param string $boardId
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function delete(string $boardId): bool
  {
    $this->request->delete(sprintf("boards/%s/", $boardId));

    return true;
  }
}
