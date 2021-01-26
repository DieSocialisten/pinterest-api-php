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
   */
  public function get(string $boardId, array $data = []): Board
  {
    $endpoint = sprintf("boards/%s/", $boardId);

    $this->master->logRequest($endpoint, $data);

    try {
      $response = $this->request->get($endpoint, $data);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint, $data);
    }

    return new Board($this->master, $response);
  }

  /**
   * Create a new board
   *
   * @param array $data
   * @return Board
   *
   * @throws PinterestException
   */
  public function create(array $data): Board
  {
    $endpoint = "boards/";

    $this->master->logRequest($endpoint, $data);

    try {
      $response = $this->request->post($endpoint, $data);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint, $data);
    }

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
   */
  public function edit(string $boardId, array $data, $fields = null): Board
  {
    $query = (!$fields) ? [] : ["fields" => $fields];
    $endpoint = sprintf("boards/%s/", $boardId);

    $this->master->logRequest($endpoint, $data);

    try {
      $response = $this->request->update($endpoint, $data, $query);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint, $data);
    }

    return new Board($this->master, $response);
  }

  /**
   * Delete a board
   *
   * @param string $boardId
   * @return bool
   *
   * @throws PinterestException
   */
  public function delete(string $boardId): bool
  {
    $endpoint = sprintf("boards/%s/", $boardId);

    $this->master->logRequest($endpoint, ['boardId' => $boardId]);

    try {
      $this->request->delete($endpoint);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint);
    }

    return true;
  }
}
