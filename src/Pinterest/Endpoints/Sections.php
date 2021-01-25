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
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Models\Section;
use DirkGroenen\Pinterest\Models\Collection;

class Sections extends Endpoint
{
  /**
   * Create a section
   *
   * @param string $board
   * @param array $data
   * @return Section
   *
   * @throws PinterestException
   */
  public function create(string $board, array $data): Section
  {
    $endpoint = sprintf("board/%s/sections/", $board);

    try {
      $response = $this->request->put($endpoint, $data);
    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }

    return new Section($this->master, ['id' => $response->data]);
  }

  /**
   * Get sections for the given board
   *
   * @param string $board
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function get(string $board, array $data = []): Collection
  {
    $endpoint = sprintf("board/%s/sections/", $board);

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection(
        $this->master,
        array_map(
          function ($r) {
            return ['id' => $r];
          },
          $response->data
        ),
        Section::class
      );

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Get pins for section
   *
   * @param string $section
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function pins(string $section, array $data = []): Collection
  {
    $endpoint = sprintf("board/sections/%s/pins/", $section);

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Pin::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Delete a board's section
   *
   * @param string $section
   * @return bool
   *
   * @throws PinterestException
   */
  public function delete(string $section): bool
  {
    $endpoint = sprintf("board/sections/%s/", $section);

    try {
      $this->request->delete($endpoint);
    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }

    return true;
  }
}
